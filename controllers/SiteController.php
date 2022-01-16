<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Employee;
use app\controllers\LogController;
use yii\web\NotFoundHttpException;

class SiteController extends Controller{

    public const ACCESS_DRIVER = 0;
    public const ACCESS_MANAGER = 1;
    public const ACCESS_ADMIN = 2;

    public function beforeAction($action){

        if($action != 'logout') $this->enableCsrfValidation = false;
        return parent::beforeAction($action);

    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionSetOnPage($count, $link){

        SiteController::accessPermission(SiteController::ACCESS_DRIVER, $this);
        if(is_numeric($count)) Yii::$app->session->set('pageSize', $count);
        return $this->redirect($link);

    }


    public function actionIndex(){

        $isGuest = Yii::$app->user->isGuest;
        if($isGuest) return $this->redirect(['site/login']); else return $this->redirect(['trace/index']);
    }

    public function actionLogin(){

        $isGuest = Yii::$app->user->isGuest;
        $error = '';
        if($isGuest){
            if (Yii::$app->request->isPost){

                $login = Yii::$app->request->post('login');
                $password = Yii::$app->request->post('password');
                $redirect = Yii::$app->request->post('redirect');

                $user = Employee::find()->where(['login' => $login ])->one();
                if($user){
                    if($user->loginLdap($password) || $user->login($password)){
                        Yii::$app->session->set('period', PeriodController::getActualId());
                        Yii::$app->user->login($user, 3600 * 24);
                        LogController::addLog(LogController::LOG_OPERATION);

                        if(isset($redirect)) return $this->redirect([urldecode($redirect)]);
                        else return $this->redirect(['site/index']);

                    } else $error = "Błędny login lub hasło";
                } else $error = "Taki użytkownik nie istnieje w systemie";
            }
        } else return $this->redirect(['site/index']);

        return $this->render('login', ['error' => $error]);

    }


    public function actionLogout(){

          Yii::$app->user->logout();
          return $this->redirect(['site/login']);

    }

    public static function accessPermission($access){

        $isGuest = Yii::$app->user->isGuest;
        if($isGuest) return false;

        $isDriver = false;

        $user_id = Yii::$app->user->id;
        $type = Yii::$app->user->identity->getAccess();

        if($type < $access) throw new NotFoundHttpException();

        return true;

    }

    public static function accessLogUser($access){

        $isGuest = Yii::$app->user->isGuest;
        if(!$isGuest){
            $type = Yii::$app->user->identity->getAccess();
            if($type >= $access) return true;
        }
        return false;
    }

}
