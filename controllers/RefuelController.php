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
use app\models\Refuel;
use app\models\Section;
use app\models\Product;
use app\models\Period;
use app\models\TypeCar;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use app\widgets\LabelPeriod;

class RefuelController extends Controller
{

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_MANAGER)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;
        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();

        $refuels = Refuel::find()->where(['period_id' => $currentPeriod]);
        $count = $refuels->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $refuels = $refuels->offset($pagination->offset)
          ->limit($pagination->limit)
          ->orderBy(['created' => SORT_DESC])
          ->all();

        return $this->render('index', [
            'refuels' => $refuels,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAll(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;
        $refuels = Refuel::find()->where('id > 0');

        if(Yii::$app->request->get('car') || intval(Yii::$app->request->get('car')) > 0)
            $refuels->andWhere(['car_id' => Yii::$app->request->get('car')]);

        if(Yii::$app->request->get('employee') || intval(Yii::$app->request->get('employee')) > 0)
            $refuels->andWhere(['employee_id' => Yii::$app->request->get('employee')]);

        $count = $refuels->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $refuels = $refuels->offset($pagination->offset)
          ->limit($pagination->limit)
          ->orderBy(['created' => SORT_DESC])
          ->all();

        $cars = Car::find()->where(['used' => 1])->all();

        return $this->render('all', [
            'refuels' => $refuels,
            'cars' => $cars,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAdd(){

        $error = '';

        if (Yii::$app->request->isPost){

            $number = Yii::$app->request->post('number');
            $section = Yii::$app->request->post('section');
            $car = Yii::$app->request->post('car');
            $date = Yii::$app->request->post('date');
            $price_fuel = Yii::$app->request->post('price_fuel');
            $amount_fuel = Yii::$app->request->post('amount_fuel');
            $discount = Yii::$app->request->post('discount');

            $min_price = SettingsController::getValue(SettingsController::MIN_PRICE_FUEL);
            $max_price = SettingsController::getValue(SettingsController::MAX_PRICE_FUEL);
            $vat_fuel = SettingsController::getValue(SettingsController::VAT_FUEL);

            if($price_fuel < $min_price || $price_fuel > $max_price) $error = 'Błędna cena za litr paliwa';
            if($discount > ($price_fuel * $amount_fuel)) $error = 'Rabat nie może być większy niż łączna cena paliwa';

            if($error == ''){

                $refuel = new Refuel;

                $refuel->number = $number;
                $refuel->section_id = $section;
                $refuel->car_id = $car;
                $refuel->created = strtotime($date);
                $refuel->fuel_price_basic = $price_fuel;
                $refuel->fuel_price = $price_fuel - ($discount / $amount_fuel);
                $refuel->fuel_amount = $amount_fuel;
                $refuel->discount = $discount;
                $refuel->period_id = PeriodController::getActualId();
                $refuel->save(false);

                $products = Yii::$app->request->post('product');
                $price = Yii::$app->request->post('product_price');
                $vat = Yii::$app->request->post('vat');

                if($products)
                    for($i = 0 ; $i < sizeof($products); $i++){
                        $product = new Product;
                        $product->name = $products[$i];
                        $product->price_brutto = $price[$i];
                        $product->vat = $vat[$i];
                        $product->price = $price[$i] * 100 / (100 + intval($vat[$i]));
                        $product->refuel_id = $refuel->id;
                        $product->save(false);
                    }

                LogController::addLog(LogController::ADD_OPERATION, $refuel->getNameObj(), $refuel->getFullObjJson());
                $this->redirect('/refuel/index');
            }

        }

        $sections = Section::find()->orderBy(['name' => SORT_DESC])->all();
        $cars = Car::find()->orderBy(['register' => SORT_DESC])->all();
        return $this->render('add', ['error' => $error, 'sections' => $sections, 'cars' => $cars]);

    }

    public function actionEdit($id){

        $error = '';
        $id = intval($id);

        $refuel = Refuel::find()->where(['id' => $id])->one();
        if(!$refuel) throw new NotFoundHttpException();
        $refuel_prev = $refuel->getFullObjJson();

        if (Yii::$app->request->isPost){

            $discount = Yii::$app->request->post('discount');
            $price_fuel = Yii::$app->request->post('price_fuel');
            $amount_fuel = Yii::$app->request->post('amount_fuel');

            $min_price = SettingsController::getValue(SettingsController::MIN_PRICE_FUEL);
            $max_price = SettingsController::getValue(SettingsController::MAX_PRICE_FUEL);
            $vat_fuel = SettingsController::getValue(SettingsController::VAT_FUEL);

            if($price_fuel < $min_price || $price_fuel > $max_price) $error = 'Błędna cena za litr paliwa';
            if($discount > ($price_fuel * $amount_fuel)) $error = 'Rabat nie może być większy niż łączna cena paliwa';

            if($error == ''){

                $refuel->number = Yii::$app->request->post('number');
                $refuel->section_id = Yii::$app->request->post('section');
                $refuel->car_id = Yii::$app->request->post('car');
                $refuel->created = strtotime(Yii::$app->request->post('date'));
                $refuel->fuel_price_basic = $price_fuel;
                $refuel->fuel_price = $refuel->fuel_price_basic - ($discount / $amount_fuel);
                $refuel->fuel_amount = Yii::$app->request->post('amount_fuel');
                $refuel->discount = Yii::$app->request->post('discount');
                $refuel->save(false);
                LogController::addLog(LogController::EDIT_OPERATION, $refuel->getNameObj(), $refuel->getFullObjJson(), $refuel_prev);
                $this->redirect('/refuel/index');

            }

        }

        $sections = Section::find()->orderBy(['name' => SORT_DESC])->all();
        $cars = Car::find()->orderBy(['register' => SORT_DESC])->all();
        $products = Product::find()->where(['refuel_id' => $refuel->id])->all();
        return $this->render('edit', ['error' => $error, 'sections' => $sections, 'cars' => $cars, 'edit' => $refuel, 'products' => $products]);

    }

    public function actionRemove($id){

        $id = intval($id);
        $refuel = Refuel::find()->where(['id' => $id])->one();
        if($refuel) {
            LogController::addLog(LogController::DELETE_OPERATION, $refuel->getNameObj(), $refuel->getFullObjJson());
            $refuel->delete();
        }
        $this->redirect('/refuel/index');

    }

    public function actionGetAll($number){

        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $refuels = Refuel::find()->where(['period_id' => $currentPeriod, 'number' => $number])->all();
        $retRefuels = [];

        foreach ($refuels as $refuel) :
            array_push($retRefuels, $refuel->getShortObj());
        endforeach;

        return json_encode($retRefuels);

    }

    public function actionCsv(){

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="tankowania-' . date('Y-m-d') .'.csv"');
        header("Content-Transfer-Encoding: UTF-8");
        header('Pragma: no-cache');

        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $refuels = Refuel::find()->where(['period_id' => $currentPeriod])->all();

        echo Refuel::instance()->getAttributeLabel("id").";".
          Refuel::instance()->getAttributeLabel("created").";".
          Refuel::instance()->getAttributeLabel("car_id").";".
          Refuel::instance()->getAttributeLabel("section_id").";".
          Refuel::instance()->getAttributeLabel("fuel_price").";".
          Refuel::instance()->getAttributeLabel("fuel_amount").";".
          Refuel::instance()->getAttributeLabel("period_id")." \r\n";

        foreach ($refuels as $refuel){
            echo "$refuel->id;$refuel->created;".$refuel->getCar().";".$refuel->getSection().";$refuel->fuel_price;$refuel->fuel_amount;$refuel->period_id\r\n";
        }

        LogController::addLog(LogController::RAPORT." CSV Tankowania");

    }

    public function actionPdf(){

        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $period = Period::find()->where(['id' => $currentPeriod])->one();
        $date_period = date("m - Y", strtotime($period->created_date));

        $types_car = TypeCar::find()->where(['active' => 1])->all();
        $vat_fuel = SettingsController::getValue(SettingsController::VAT_FUEL);

        foreach($types_car as $type_car){

            $cars = array_column(Car::find()->where(['used' => 1, 'type_id' => $type_car->id])->all(), 'id');
            $sections_system = Section::find()->all();

            $tpfb = 0;
            $tppn = 0;
            $tppb = 0;
            $sections = array();

            $document = isset($_GET['document']) ? $_GET['document'] : '';

            foreach ($sections_system as $section){

                $section->fuel_brutto =
                  Refuel::find()
                  ->where(['period_id' => $currentPeriod, 'section_id' => $section->id, 'number' => $document])
                  ->andWhere(['in', 'car_id', $cars])
                  ->sum('fuel_price_basic * fuel_amount - discount');

                $section->products_netto =
                    (new \yii\db\Query())
                    ->from('product')
                    ->leftJoin('refuel', 'refuel.id = product.refuel_id')
                    ->where(['refuel.period_id' => $currentPeriod, 'refuel.section_id' => $section->id, 'refuel.number' => $document])
                    ->andWhere(['in', 'refuel.car_id', $cars])
                    ->sum('price');

                $section->products_brutto =
                    (new \yii\db\Query())
                    ->from('product')
                    ->leftJoin('refuel', 'refuel.id = product.refuel_id')
                    ->where(['refuel.period_id' => $currentPeriod, 'refuel.section_id' => $section->id, 'refuel.number' => $document])
                    ->andWhere(['in', 'refuel.car_id', $cars])
                    ->sum('price_brutto');

                $tpfb += $section->fuel_brutto;
                $tppn += $section->products_netto;
                $tppb += $section->products_brutto;

                if($section->fuel_brutto + $section->products_netto + $section->products_brutto != 0) array_push($sections, $section);
            }

            $type_car->total_fuel_brutto = $tpfb;
            $type_car->total_product_netto = $tppn;
            $type_car->total_product_brutto = $tppb;
            $type_car->sections = $sections;

        }

        LogController::addLog(LogController::RAPORT." PDF Tankowania");

        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8,
            'content' => $this->renderPartial('pdf', [
                  'list' => $types_car,
                  'document' => $document,
                  'vat' => $vat_fuel
                ], true),
            'cssFile' => 'css/pdf.css',
            'options' => [
                'title' => 'Zestawienie tankowania paliwa',
            ],
        ]);

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $pdf->options = array_merge($pdf->options , [
            'fontDir' => array_merge($fontDirs, [ Yii::$app->basePath . '/web/font']),  // make sure you refer the right physical path
            'fontdata' => array_merge($fontData, [
                'popins' => [
                    'R' => 'Poppins-Regular.ttf',
                    'I' => 'Poppins-Italic.ttf',
                    'B' => 'Poppins-SemiBold.ttf',
                ]
            ])
        ]);


        return $pdf->render();

    }


}
