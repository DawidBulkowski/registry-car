<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Employee;
use yii\data\Pagination;

class EmployeeController extends Controller
{

    public function beforeAction($action){


          if(!SiteController::accessPermission(SiteController::ACCESS_ADMIN)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $isGuest = Yii::$app->user->isGuest;

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;

        $employees = Employee::find();
        $count = $employees->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $employees = $employees->offset($pagination->offset)
          ->limit($pagination->limit)
          ->all();

        return $this->render('index', [
            'employees' => $employees,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAdd(){

        $isGuest = Yii::$app->user->isGuest;
        $error = '';

        if (Yii::$app->request->isPost){

            $name = Yii::$app->request->post('name');
            $surname = Yii::$app->request->post('surname');
            $kontrahent = Yii::$app->request->post('kontrahent');
            $login = Yii::$app->request->post('login');
            $pass = Yii::$app->request->post('pass');
            $type = Yii::$app->request->post('type');
            $work = Yii::$app->request->post('work');

            $employee = Employee::find()->where(['login' => $login])->orWhere(['kontrahent' => $kontrahent])->one();

            if(!$employee){

                $employee = new Employee;
                $employee->name = $name;
                $employee->surname = $surname;
                $employee->login = $login;
                $employee->passwo = md5($pass);
                $employee->kontrahent = $kontrahent;
                $employee->access = $type;
                $employee->works = $work;

                $employee->save(false);
                LogController::addLog(LogController::ADD_OPERATION, $employee->getNameObj(), $employee->getFullObjJson());
                $this->redirect('/employee/index');

            } else {
                $error = "Pracownik o danym loginie lub numerze kontrahenta istnieje już w systemie";
            }

        }

        return $this->render('add', [ 'error' => $error ]);

    }

    public function actionEdit($id){

        $id = intval($id);
        $isGuest = Yii::$app->user->isGuest;
        $employee = Employee::find()->where(['id' => $id])->one();
        if(!$employee) throw new CHttpException(404,'Not Found');
        $employee_prev = $employee->getFullObjJson();

        $error = '';

        if (Yii::$app->request->isPost){

            $employee->name = Yii::$app->request->post('name');
            $employee->surname = Yii::$app->request->post('surname');
            $employee->login = Yii::$app->request->post('login');
            $employee->kontrahent = Yii::$app->request->post('kontrahent');
            $employee->access = Yii::$app->request->post('type');
            $employee->works = Yii::$app->request->post('work');
            if(Yii::$app->request->post('pass') != '') $employee->passwo = md5(Yii::$app->request->post('pass'));
            $employee->save(false);
            LogController::addLog(LogController::EDIT_OPERATION, $employee->getNameObj(), $employee->getFullObjJson(), $employee_prev);
            $this->redirect('/employee/index');

        }

        return $this->render('edit', ['edit' => $employee, 'error' => $error ]);

    }

    public function actionRemove($id){

        $id = intval($id);
        $employee = Employee::find()->where(['id' => $id])->one();
        if($employee) {
            LogController::addLog(LogController::DELETE_OPERATION, $employee->getNameObj(), $employee->getFullObjJson());
            $employee->delete();
        }
        $this->redirect('/employee/index');

    }

    public function actionCsv(){

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="pracownicy-' . date('Y-m-d') .'.csv"');
        header("Content-Transfer-Encoding: UTF-8");
        header('Pragma: no-cache');

        $employees = Employee::find()->all();

        echo Employee::instance()->getAttributeLabel("id").";".
          Employee::instance()->getAttributeLabel("name").";".
          Employee::instance()->getAttributeLabel("surname").";".
          Employee::instance()->getAttributeLabel("login").";".
          Employee::instance()->getAttributeLabel("kontrahent").";".
          Employee::instance()->getAttributeLabel("access").";".
          Employee::instance()->getAttributeLabel("works")." \r\n";

        foreach ($employees as $employee){
            echo "$employee->id;$employee->name;$employee->surname;$employee->login;$employee->kontrahent;".$employee->getTypeEmployee().";".$employee->getWorksEmployee()."\r\n";
        }

        LogController::addLog(LogController::RAPORT." CSV Pracownicy");

    }


}
