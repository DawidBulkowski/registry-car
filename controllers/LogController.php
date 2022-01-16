<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Log;
use yii\data\Pagination;

class LogController extends Controller
{

    public const ADD_OPERATION = 'Dodanie';
    public const EDIT_OPERATION = 'Edycja';
    public const DELETE_OPERATION = 'Usuwanie';
    public const LOG_OPERATION = 'Logowanie';
    public const CHANGE_OPERATION = 'Zmiana';
    public const CLOSE_OPERATION = 'Zamknięcie';
    public const RAPORT = 'Generowanie raportu';

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_ADMIN)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;

        $logs = Log::find();

        if(Yii::$app->request->get('szukaj'))
            $logs->where('LOWER(value) LIKE :szukaj', array(':szukaj' => '%'.Yii::$app->request->get('szukaj').'%'));

        $count = $logs->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $logs = $logs->offset($pagination->offset)
          ->limit($pagination->limit)
          ->orderBy(['created' => SORT_DESC])
          ->all();

        return $this->render('index', [
            'logs' => $logs,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionDetails($id){

        $id = intval($id);

        $log = Log::find()->where(['id' => $id])->one();
        if(!$log) throw new CHttpException(404,'Not Found');
        return $this->render('detail', ['log' => $log]);

    }

    public static function addLog($operation, $value = '', $details = '', $prev_details = ''){

        $nameLoggedUser = Yii::$app->user->identity->getFullNameToLog();
        $log = new Log;
        $log->value = $operation." ".$value." przez ".$nameLoggedUser;
        $log->details = $details;
        $log->details_prev = $prev_details;
        $log->save(false);

    }

    public static function convertJsonToString($data){
        $data = json_decode($data);
        foreach ($data as $key => $value) {
            echo "Pole <b>$key</b> ma wartość <b>$value</b> <br/>";
        }
    }


}
