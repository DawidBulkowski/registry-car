<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Trace;
use app\models\Employee;
use app\models\Car;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class TraceController extends Controller
{

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_DRIVER)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;
        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();

        $traces = Trace::find()->where(['period_id' => $currentPeriod]);

        if(Yii::$app->request->get('car') || intval(Yii::$app->request->get('car')) > 0){
            $traces->andWhere(['car_id' => Yii::$app->request->get('car')]);
        }

        if(Yii::$app->request->get('employee') || intval(Yii::$app->request->get('employee')) > 0){
            $traces->andWhere(['employee_id' => Yii::$app->request->get('employee')]);
        }

        $count = $traces->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $traces = $traces->offset($pagination->offset)
          ->limit($pagination->limit)
          ->orderBy(['start_date' => SORT_DESC])
          ->all();

        $cars = Car::find()->where(['used' => 1])->all();
        $employees = Employee::find()->where(['works' => 1])->all();

        return $this->render('index', [
            'traces' => $traces,
            'cars' => $cars,
            'employees' => $employees,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAll(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;
        $traces = Trace::find()->where('id > 0');

        if(Yii::$app->request->get('car') || intval(Yii::$app->request->get('car')) > 0)
            $traces->andWhere(['car_id' => Yii::$app->request->get('car')]);

        if(Yii::$app->request->get('employee') || intval(Yii::$app->request->get('employee')) > 0)
            $traces->andWhere(['employee_id' => Yii::$app->request->get('employee')]);

        $count = $traces->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $traces = $traces->offset($pagination->offset)
          ->limit($pagination->limit)
          ->orderBy(['start_date' => SORT_DESC])
          ->all();

        $cars = Car::find()->where(['used' => 1])->all();
        $employees = Employee::find()->where(['works' => 1])->all();

        return $this->render('all', [
            'traces' => $traces,
            'cars' => $cars,
            'employees' => $employees,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAdd(){

        $error = '';
        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->getAccess() == 0) $isDriver = true; else $isDriver = false;

        if (Yii::$app->request->isPost){

            $employee = Yii::$app->request->post('employee');
            $car = Yii::$app->request->post('car');
            $description = Yii::$app->request->post('description');
            $start_date = Yii::$app->request->post('start_date');
            $end_date = Yii::$app->request->post('end_date');
            $km_corp = Yii::$app->request->post('km_corp');
            $km_priv = Yii::$app->request->post('km_priv');
            $liczn = Yii::$app->request->post('liczn');

            $last_trace_car = Trace::find()->where(['car_id' => $car])->andWhere('end_date <='.time())->orderBy(['km_final' => SORT_DESC])->one();
            
            if($last_trace_car){
                $control_value = $last_trace_car->km_final + $km_corp + $km_priv;
                if($control_value != $liczn) $error = "Błędny stan licznika - wcześniejszy stan: ".$last_trace_car->km_final;
            }

            $existing_trace_date = Trace::find()->where(['car_id' => $car])->orderBy(['km_final' => SORT_DESC])->one();

            if($error == ''){
                $trace = new Trace;
                $trace->car_id = $car;

                if($isDriver) $trace->employee_id = Yii::$app->user->id;
                else $trace->employee_id = $employee;

                $trace->description = $description;
                $trace->start_date = strtotime($start_date);
                if($end_date != '') $trace->end_date = strtotime($end_date);
                $trace->km_corp = $km_corp;
                $trace->km_priv = $km_priv;
                $trace->km_final = $liczn;
                $trace->period_id = PeriodController::getActualId();

                $trace->save(false);

                LogController::addLog(LogController::ADD_OPERATION, $trace->getNameObj(), $trace->getFullObjJson());
                $this->redirect('/trace/index');
            }

        }

        $employees = array();
        $cars = Car::find()->where(['used' => 1])->all();

        if($isDriver) $ownCar = Car::find()->where(['used' => 1])->andWhere(['employee_id' => Yii::$app->user->id])->orderBy(['register' => SORT_DESC]);
        else $employees = Employee::find()->where(['works' => 1])->where(['access' => 0])->all();

        return $this->render('add', ['error' => $error, 'cars' => $cars, 'employees' => $employees, 'isDriver' => $isDriver]);

    }

    public function actionEdit($id){

        $id = intval($id);
        $error = '';
        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->getAccess() == 0) $isDriver = true; else $isDriver = false;
        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();

        $trace = Trace::find()->where(['id' => $id, 'period_id' => $currentPeriod])->one();
        if(!$trace) throw new NotFoundHttpException();
        $prev_trace = $trace->getFullObjJson();

        if (Yii::$app->request->isPost){

            $car_id = Yii::$app->request->post('car');
            $liczn = Yii::$app->request->post('liczn');
            $km_corp = Yii::$app->request->post('km_corp');
            $km_priv = Yii::$app->request->post('km_priv');

            $last_trace_car = Trace::find()->where(['car_id' => $car_id])->andWhere('end_date < '.$trace->end_date)->orderBy(['km_final' => SORT_DESC])->one();
            if($last_trace_car){
                $control_value = $last_trace_car->km_final + $km_corp + $km_priv;
                if($control_value != $liczn) $error = "Błędny stan licznika - wcześniejszy stan: ".$last_trace_car->km_final;
            }

            $next_trace_car = Trace::find()->where(['car_id' => $car_id])->andWhere('start_date > '.$trace->end_date)->orderBy(['km_final' => SORT_DESC])->one();
            if($next_trace_car){
                $control_value = $liczn + $km_corp + $km_priv;
                if($control_value != $next_trace_car->km_final) $error = "Błędny stan licznika - następny wprowadzony stan: ".$next_trace_car->km_final;
            }

            if($error == ''){

                $trace->car_id = Yii::$app->request->post('car');
                if(!$isDriver) $trace->employee_id = Yii::$app->request->post('employee');
                $trace->description = Yii::$app->request->post('description');
                $trace->start_date = strtotime(Yii::$app->request->post('start_date'));
                $trace->end_date = strtotime(Yii::$app->request->post('end_date'));
                $trace->km_corp = $km_corp;
                $trace->km_priv = $km_priv;
                $trace->km_final = $liczn;
                $trace->period_id = PeriodController::getActualId();
                $trace->save(false);
                LogController::addLog(LogController::EDIT_OPERATION, $trace->getNameObj(), $trace->getFullObjJson(), $prev_trace);
                $this->redirect('/trace/index');

            }

        }

        $employees = Employee::find()->where(['works' => 1])->all();
        $cars = Car::find()->all();
        return $this->render('edit', ['edit' => $trace, 'error' => $error, 'isDriver' => $isDriver, 'employees' => $employees, 'cars' => $cars]);

    }

    public function actionRemove($id){

        $id = intval($id);
        $trace = Trace::find()->where(['id' => $id]);
        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->getAccess() == 0) $trace->andWhere(['employee_id' => Yii::$app->user->user_id]);
        $trace = $trace->one();
        if($trace) {
            LogController::addLog(LogController::DELETE_OPERATION, $trace->getNameObj(), $trace->getFullObjJson());
            $trace->delete();
        }
        $this->redirect('/trace/index');

    }

    public function actionCsv(){

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="wyjazdy-' . date('Y-m-d') .'.csv"');
        header("Content-Transfer-Encoding: UTF-8");
        header('Pragma: no-cache');

        $traces = Trace::find()->all();

        echo Trace::instance()->getAttributeLabel("id").";".
          Trace::instance()->getAttributeLabel("car_id").";".
          Trace::instance()->getAttributeLabel("employee_id").";".
          Trace::instance()->getAttributeLabel("description").";".
          Trace::instance()->getAttributeLabel("start_date").";".
          Trace::instance()->getAttributeLabel("end_date").";".
          Trace::instance()->getAttributeLabel("km_corp").";".
          Trace::instance()->getAttributeLabel("km_priv").";".
          Trace::instance()->getAttributeLabel("km_final").";".
          Trace::instance()->getAttributeLabel("period_id")." \r\n";

        foreach ($traces as $trace){
            echo "$trace->id;".$trace->getCar().";".$trace->getEmployee().";$trace->description;".date('d/m/Y H:i', $trace->start_date).";".date('d/m/Y H:i', $trace->end_date).";$trace->km_corp;$trace->km_priv;$trace->km_final;$trace->period_id\r\n";
        }

        LogController::addLog(LogController::RAPORT." CSV Wyjazdy");

    }


}
