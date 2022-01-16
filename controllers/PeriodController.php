<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Period;
use yii\data\Pagination;
use DateTime;

class PeriodController extends Controller
{

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_MANAGER)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $currentPeriod = isset($_SESSION['period']) ? $_SESSION['period'] : PeriodController::getActualId();
        $periods = Period::find()->orderBy(['id' => SORT_DESC])->all();

        return $this->render('index', [
            'periods' => $periods,
            'current' => $currentPeriod
        ]);

    }

    public function actionChange($id){
        $id = intval($id);
        $period = Period::find()->where(['id' => $id])->one();
        if($period) {
            LogController::addLog(LogController::CHANGE_OPERATION." Okres rozliczeniowy");
            Yii::$app->session->set('period', $period->id);
        }
        $this->redirect('/period/index');
    }

    public function actionClose(){

        if (Yii::$app->request->isPost){

            $period = Period::find()->where(['completed' => 0])->one();
            $closed_days = intval(SettingsController::getValue(SettingsController::PERIOD_CLOSE));

            $now_date = date("Y-m-d");
            $next_date = date("Y-m-d", strtotime("+".$closed_days." days", strtotime($period->created_date)));

            $field_data = Yii::$app->request->post('date');
            $close = Yii::$app->request->post('close');

            if($now_date >= $next_date){

                if($field_data) $end_date = $field_data;
                else $end_date = $now_date;

                if($close){
                    if($field_data) $period->end_date = $field_data;
                    else $period->end_date = new \yii\db\Expression('NOW()');
                    $period->completed = 1;
                    $period->save(false);

                    $period = new Period;
                    if($field_data) {
                        $tmp_date = new DateTime($field_data);
                        $tmp_date->modify('+1 day');
                        $period->created_date = $tmp_date->format('Y-m-d');
                    }
                    else $period->created_date = new \yii\db\Expression('DATE_ADD(NOW(), INTERVAL 1 DAY)');
                    $period->save(false);
                    Yii::$app->session->set('period', PeriodController::getActualId());

                    LogController::addLog(LogController::CLOSE_OPERATION." Okres rozliczeniowy");
                    $this->redirect('/period/index');
                } else {
                    $this->redirect('/period/index?accept=1&from='.$period->created_date.'&to='.$end_date);
                }

            } else {
                $this->redirect('/period/index?error=1&date='.$next_date);
            }
        }

    }

    public static function getActualId(){
        $last_period = Period::find()->where(['completed' => 0])->one();
        if(!$last_period){
            $last_period = new Period;
            $last_period->created_date = new \yii\db\Expression('NOW()');
            $last_period->save(false);
        }
        return $last_period->id;
    }



}
