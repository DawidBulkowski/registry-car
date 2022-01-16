<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Phone;
use app\models\Employee;
use yii\data\Pagination;

class PhoneController extends Controller
{

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_MANAGER)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;
        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();

        $phones = Phone::find()->where(['period_id' => $currentPeriod]);
        $count = $phones->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $phones = $phones->offset($pagination->offset)
          ->limit($pagination->limit)
          ->all();

        return $this->render('index', [
            'phones' => $phones,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAdd(){

        $error = '';

        if (Yii::$app->request->isPost){

            $employee_id = Yii::$app->request->post('employee');
            $value = Yii::$app->request->post('value');
            $period_id = PeriodController::getActualId();

            $phone = new Phone;
            $phone->employee_id = $employee_id;
            $phone->value = $value;
            $phone->period_id = $period_id;
            $phone->save(false);

            LogController::addLog(LogController::ADD_OPERATION, $phone->getNameObj(), $phone->getFullObjJson());

            $this->redirect('/phone/index');

        }

        $employees_added = Phone::find()->where(['period_id' => PeriodController::getActualId()])->all();
        $employees_added = array_column($employees_added, 'employee_id');
        $employees = Employee::find()->where(['works' => 1])->andWhere(['not in', 'id', $employees_added])->orderBy(['surname' => SORT_ASC, 'name' => SORT_ASC])->all();
        return $this->render('add', ['employees' => $employees]);

    }

    public function actionEdit($id){

        $id = intval($id);

        $phone = Phone::find()->where(['id' => $id])->one();
        if(!$phone) throw new CHttpException(404,'Not Found');
        $phone_prev = $phone->getFullObjJson();

        if (Yii::$app->request->isPost){

            $phone->value = Yii::$app->request->post('value');
            $phone->save(false);

            LogController::addLog(LogController::EDIT_OPERATION, $phone->getNameObj(), $phone->getFullObjJson(), $phone_prev);
            $this->redirect('/phone/index');

        }

        $employee = Employee::find()->where(['id' => $phone->employee_id])->one();
        return $this->render('edit', ['edit' => $phone, 'employee' => $employee]);

    }

    public function actionRemove($id){

        $id = intval($id);
        $phone = Phone::find()->where(['id' => $id])->one();
        if($phone) {
            LogController::addLog(LogController::DELETE_OPERATION, $phone->getNameObj(), $phone->getFullObjJson());
            $phone->delete();
        }
        $this->redirect('/phone/index');

    }

    public function actionCsv(){

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="telefony-' . date('Y-m-d') .'.csv"');
        header("Content-Transfer-Encoding: UTF-8");
        header('Pragma: no-cache');

        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $phones = Phone::find()->where(['period_id' => $currentPeriod]);

        echo Phone::instance()->getAttributeLabel("id").";".
          Phone::instance()->getAttributeLabel("employee_id").";".
          Phone::instance()->getAttributeLabel("value")." \r\n";

        foreach ($phones as $phone){
            echo "$phone->id;$phone->getEmployee();$phone->value\r\n";
        }

        LogController::addLog(LogController::RAPORT." CSV Telefony");

    }


}
