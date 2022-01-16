<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Section;
use app\models\Employee;
use app\models\Car;
use app\models\Refuel;
use app\models\Period;
use app\models\Phone;
use app\models\Trace;
use app\models\CarEmployee;
use app\controllers\SettingsController;
use app\controllers\LogController;
use yii\data\Pagination;

class StatisticsController extends Controller
{

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_MANAGER)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionRefuel(){

        $refuels = Refuel::find()->all();
        $cars = Car::find()->where(['used' => 1])->all();
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        $vat = intval(SettingsController::getValue(SettingsController::VAT_FUEL));

        foreach ($cars as $car) :
            $start_date = strtotime('01-01-'.$year);
            $end_date = strtotime('31-12-'.$year);
            $car->refuelAmount = Refuel::find()->where(['car_id' => $car->id])->andWhere(['between', 'created', $start_date, $end_date])->sum('fuel_amount');
            $car->refuelPrice = Refuel::find()->where(['car_id' => $car->id])->andWhere(['between', 'created', $start_date, $end_date])->sum('fuel_price * fuel_amount');
        endforeach;

        return $this->render('refuel', ['cars' => $cars, 'vat' => $vat, 'year' => $year]);

    }

    public function actionEmployees(){

        $employees = Employee::find()->where(['works' => 1, 'access' => 0])->all();
        $cars = Car::find()->where(['used' => 1])->all();
        $period = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $periodOU = $this->getPrevPeriodsOU($period);

        foreach ($employees as $employee) :

            $carsArray = [];

            foreach ($cars as $car) :

                $distance = $this->distancePrivatePeriodsByCarEmployee($car, $employee, $period);
                if($distance != 0){
                    $ce = new CarEmployee;
                    $ce->carName = $car->getFullName();
                    $ce->costBruttoForKilometer = round($this->costBruttoForKilometerByCar($car, $periodOU, $period), 2);
                    $ce->distancePrivate = $this->distancePrivatePeriodsByCarEmployee($car, $employee, $period);
                    $ce->finalCost = round($ce->distancePrivate * $ce->costBruttoForKilometer, 2);
                    array_push($carsArray, $ce);
                }

            endforeach;

            $employee->listOfCars = $carsArray;

        endforeach;

        return $this->render('employees', [
            'employees' => $employees
        ]);

    }

    public function actionCars(){

        $cars = Car::find()->where(['used' => 1])->all();
        $period = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $periodOU = $this->getPrevPeriodsOU($period);

        foreach ($cars as $car) :

            $fuel_amountBOR = $this->amountFuelPeriodsByCar($car, $period);;
            $fuel_priceBOR = Refuel::find()->where(['car_id' => $car->id, 'period_id' => $period])->sum('fuel_price');
            $distanceBOR = $this->distancePeriodsByCar($car, $period);
            $sumPriceBOR = $this->priceFuelPeriodsByCar($car, $period);

            $fuel_amountOU = $this->amountFuelPeriodsByCar($car, $periodOU);
            $distanceOU = $this->distancePeriodsByCar($car, $periodOU);

            $car->averageBOR = $this->averageFuelConsumptionByCarOU($car, $period);
            $car->distanceBOR = $distanceBOR == 0 ? 0 : $distanceBOR;

            $car->averageOU = $distanceOU == 0 ? 0 : (100 * $fuel_amountOU / $distanceOU);
            $car->averagePriceFuelBOR = $fuel_amountBOR == 0 ? 0 : $sumPriceBOR / $fuel_amountBOR;

        endforeach;

        return $this->render('cars', [
            'cars' => $cars,
            'ou' => $periodOU
        ]);

    }

    public function actionPif(){

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="sam_i_tel-' . date('Y-m-d') .'.txt"');
        header("Content-Transfer-Encoding: UTF-8");
        header('Pragma: no-cache');

        LogController::addLog(LogController::RAPORT." PIF Przejazdy");

        $trace_code = SettingsController::getValue(SettingsController::TRACE_CODE);
        $phone_code = SettingsController::getValue(SettingsController::PHONE_CODE);
        $scope = SettingsController::getValue(SettingsController::SCOPE_PRICE);
        $vat_phone = SettingsController::getValue(SettingsController::VAT_PHONE);

        $period = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $periodOU = $this->getPrevPeriodsOU($period);

        $employees = Employee::find()->where(['works' => 1, 'access' => 0])->all();

        foreach ($employees as $employee){

            $phone = Phone::find()->where(['employee_id' => $employee->id, 'period_id' => $period])->one();
            $distancePrivateBOR = $this->distancePrivatePeriodsByEmployee($employee, $period);
            $finalCost = $this->finalCostForPrivateTraceByPeriod($employee, $period, $periodOU);

            if(empty($distancePrivateBOR)) $distancePrivateBOR = 0;
            $costBruttoForKilometer = $distancePrivateBOR == 0 ? 0 : round($finalCost / $distancePrivateBOR, 2);

            if($distancePrivateBOR * doubleval($costBruttoForKilometer) >= doubleval($scope)){
                $line = "$employee->kontrahent;$trace_code;$distancePrivateBOR;$costBruttoForKilometer\r\n";
                echo str_replace(".", ",", $line);
            }

            if($phone){
                $costPhoneBrutto = (100 + intval($vat_phone)) / 100 * $phone->value;
                if(doubleval($costPhoneBrutto) >= doubleval($scope))
                $line = "$employee->kontrahent;$phone_code;1;$costPhoneBrutto\r\n";
                echo str_replace(".", ",", $line);
            }



        }

    }

    public function averageFuelConsumptionByCarOU($car, $periods){
        if($this->distancePeriodsByCar($car, $periods) == 0) return 0;
        return 100 * $this->amountFuelPeriodsByCar($car, $periods) / $this->distancePeriodsByCar($car, $periods);
    }

    public function averageCostOfFuelByCar($car, $periods){
        if($this->amountFuelPeriodsByCar($car, $periods) == 0) return 0;
        return $this->priceFuelPeriodsByCar($car, $periods) / $this->amountFuelPeriodsByCar($car, $periods);
    }

    public function costOfFuelPeriodsByCar($car, $periods, $periodOR){
        return $this->distancePeriodsByCar($car, $periods) * $this->averageFuelConsumptionByCarOU($car, $periods) * $this->averageCostOfFuelByCar($car, $periodOR);
    }

    public function costOfFuelPrivatePeriodsByCar($car, $periods, $periodOR){
        return $this->distancePrivatePeriodsByCar($car, $periodOR) * $this->averageFuelConsumptionByCarOU($car, $periods) * $this->averageCostOfFuelByCar($car, $periodOR) / 100;
    }

    public function costOfFuelPeriodsByCarEmployee($car, $employee, $periods, $periodOR){
        return $this->distancePrivatePeriodsByCarEmployee($car, $employee, $periods) * $this->averageFuelConsumptionByCarOU($car, $periods) * $this->averageCostOfFuelByCar($car, $periodOR) / 100;
    }

    public function costOfFuelPeriodsByEmployee($employee, $periods, $periodOR){
        $cars = Car::find()->where(['used' => 1])->all();
        $sum = 0;

        foreach ($cars as $car) :
            $sum += $this->costOfFuelPeriodsByCarEmployee($car, $employee, $periods, $periodOR);
        endforeach;

        return $sum;
    }

    public function costBruttoForKilometer($employee, $periods, $periodOR){
        $marza = SettingsController::getValue(SettingsController::MARZA);
        if($this->distancePrivatePeriodsByEmployee($employee, $periodOR) == 0) return 0;
        return $this->costOfFuelPeriodsByEmployee($employee, $periods, $periodOR) / $this->distancePrivatePeriodsByEmployee($employee, $periodOR) * ((100 + intval($marza)) / 100);
    }

    public function costBruttoForKilometerByCar($car, $periods, $periodOR){
        $marza = SettingsController::getValue(SettingsController::MARZA);
        if($this->distancePrivatePeriodsByCar($car, $periodOR) == 0) return 0;
        return $this->costOfFuelPrivatePeriodsByCar($car, $periods, $periodOR) * ((100 + intval($marza)) / 100) / $this->distancePrivatePeriodsByCar($car, $periodOR);
    }

    public function finalCostForPrivateTraceByPeriod($employee, $period, $periodOU){

        $cars = Car::find()->where(['used' => 1])->all();
        $final = 0;

        foreach ($cars as $car) :
            $distance = $this->distancePrivatePeriodsByCarEmployee($car, $employee, $period);
            if($distance != 0){
                $costBruttoForKilometer = round($this->costBruttoForKilometerByCar($car, $periodOU, $period), 2);
                $distancePrivate = $this->distancePrivatePeriodsByCarEmployee($car, $employee, $period);
                $final += round($distancePrivate * $costBruttoForKilometer, 2);
            }
        endforeach;

        return $final;
    }

    public function priceFuelPeriodsByCar($car, $periods){
        return Refuel::find()->where(['car_id' => $car->id])->andWhere(['in', 'period_id', $periods])->sum('fuel_amount * fuel_price');
    }

    public function amountFuelPeriodsByCar($car, $periods){
        return Refuel::find()->where(['car_id' => $car->id])->andWhere(['in', 'period_id', $periods])->sum('fuel_amount');
    }

    public function distancePeriodsByCar($car, $periods){
        return Trace::find()->where(['car_id' => $car->id])->andWhere(['in', 'period_id', $periods])->sum('km_priv + km_corp');
    }

    public function distancePrivatePeriodsByCar($car, $periods){
        return Trace::find()->where(['car_id' => $car->id])->andWhere(['in', 'period_id', $periods])->sum('km_priv');
    }

    public function distancePrivatePeriodsByEmployee($employee, $periods){
        return Trace::find()->where(['employee_id' => $employee->id])->andWhere(['in', 'period_id', $periods])->sum('km_priv');
    }

    public function distancePrivatePeriodsByCarEmployee($car, $employee, $periods){
        return Trace::find()->where(['car_id' => $car->id, 'employee_id' => $employee->id])->andWhere(['in', 'period_id', $periods])->sum('km_priv');
    }

    public function getPrevPeriodsOU($period){
        return array_filter(array_column(Period::find()->where('id <= '.$period)->limit(intval(SettingsController::getValue(SettingsController::OU_NUMBER)))->orderBy(['id' => SORT_DESC])->all(), 'id'));
    }

}
