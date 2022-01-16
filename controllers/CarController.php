<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Car;
use app\models\Employee;
use app\models\Section;
use app\models\TypeCar;
use yii\data\Pagination;
use yii\helpers\Json;

class CarController extends Controller
{

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_ADMIN)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;

        $cars = Car::find();
        $count = $cars->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $cars = $cars->offset($pagination->offset)
          ->limit($pagination->limit)
          ->all();

        return $this->render('index', [
            'cars' => $cars,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAdd(){

        $error = '';

        if (Yii::$app->request->isPost){

            $register = Yii::$app->request->post('register');
            $employee = Yii::$app->request->post('employee');
            $description = Yii::$app->request->post('description');
            $fuel = Yii::$app->request->post('fuel');
            $type = Yii::$app->request->post('type');
            $norm = Yii::$app->request->post('norm');
            $used = Yii::$app->request->post('used');
            $section = Yii::$app->request->post('section');
            $symbol = Yii::$app->request->post('symbol');

            $car = Car::find()->where(['register' => $register])->one();

            if(!$car){
                $car = new Car;
                $car->register = $register;
                $car->description = $description;
                $car->employee_id = $employee;
                $car->fuel = $fuel;
                $car->type_id = $type;
                $car->norm = $norm;
                $car->used = $used;
                $car->symbol = $symbol;
                $car->section_id = $section;
                $car->save(false);

                LogController::addLog(LogController::ADD_OPERATION, $car->getNameObj(), $car->getFullObjJson());

                $this->redirect('/car/index');
            } else {
                $error = "Samochód o tym numerze rejestracyjnym już istnieje w systemie";
            }

        }

        $typesCar = TypeCar::find()->where(['active' => 1])->all();
        $employees = Employee::find()->where(['works' => 1])->orderBy(['surname' => SORT_ASC, 'name' => SORT_ASC])->all();
        $sections = Section::find()->orderBy(['name' => SORT_ASC])->all();
        return $this->render('add', ['employees' => $employees, 'sections' => $sections, 'typesCar' => $typesCar, 'error' => $error]);

    }

    public function actionEdit($id){

        $id = intval($id);
        $error = '';

        $car = Car::find()->where(['id' => $id])->one();
        if(!$car) throw new CHttpException(404,'Not Found');
        $car_prev = $car->getFullObjJson();

        if (Yii::$app->request->isPost){

            $car->register = Yii::$app->request->post('register');
            $car->description = Yii::$app->request->post('description');
            $car->employee_id = Yii::$app->request->post('employee');
            $car->fuel = Yii::$app->request->post('fuel');
            $car->type_id = Yii::$app->request->post('type');
            $car->norm = Yii::$app->request->post('norm');
            $car->used = Yii::$app->request->post('used');
            $car->symbol = Yii::$app->request->post('symbol');
            $car->section_id = Yii::$app->request->post('section');

            $car->save(false);
            LogController::addLog(LogController::EDIT_OPERATION, $car->getNameObj(), $car->getFullObjJson(), $car_prev);
            $this->redirect('/car/index');

        }

        $typesCar = TypeCar::find()->where(['active' => 1])->all();
        $employees = Employee::find()->where(['works' => 1])->orderBy(['surname' => SORT_ASC, 'name' => SORT_ASC])->all();
        $sections = Section::find()->orderBy(['name' => SORT_ASC])->all();
        return $this->render('edit', ['edit' => $car, 'employees' => $employees, 'sections' => $sections, 'typesCar' => $typesCar, 'error' => $error ]);

    }

    public function actionRemove($id){

        $id = intval($id);
        $car = Car::find()->where(['id' => $id])->one();
        if($car) {
            LogController::addLog(LogController::DELETE_OPERATION, $car->getNameObj(), $car->getFullObjJson());
            $car->delete();
        }
        $this->redirect('/car/index');

    }

    public function actionCsv(){

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="samochody-' . date('Y-m-d') .'.csv"');
        header("Content-Transfer-Encoding: UTF-8");
        header('Pragma: no-cache');

        $cars = Car::find()->all();

        echo Car::instance()->getAttributeLabel("id").";".
          Car::instance()->getAttributeLabel("register").";".
          Car::instance()->getAttributeLabel("employee_id").";".
          Car::instance()->getAttributeLabel("section_id").";".
          Car::instance()->getAttributeLabel("description").";".
          Car::instance()->getAttributeLabel("fuel").";".
          Car::instance()->getAttributeLabel("type").";".
          Car::instance()->getAttributeLabel("norm").";".
          Car::instance()->getAttributeLabel("used")." \r\n";

        foreach ($cars as $car){
            echo "$car->id;$car->register;".$car->getEmployee().";".$car->getSection().";$car->description;$car->fuel;$car->type;$car->norm;".$car->getUsed()."\r\n";
        }

        LogController::addLog(LogController::RAPORT." CSV Samochody");

    }

    public function actionGetAllByEmployee($employee_id){
        $employee_id = intval($employee_id);
        $cars = Car::find()->where(['employee_id' => $employee_id, 'used' => 1])->all();
        return Json::encode($cars, $asArray = true);
    }

    public function actionGetAllBySection($section_id){
        $section_id = intval($section_id);
        $cars = Car::find()->where(['section_id' => $section_id, 'used' => 1])->all();
        return Json::encode($cars, $asArray = true);
    }


}
