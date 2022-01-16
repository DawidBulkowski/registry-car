<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use app\controllers\PeriodController;
use app\models\Period;

class LabelPeriod extends Widget {

    public function init(){
        parent::init();
    }

    public function run(){
        $actual_period = PeriodController::getActualId();
        $current_period = isset($_SESSION['period']) ? $_SESSION['period'] : $actual_period;
        $period = Period::find()->where(['id' => $current_period])->one();

        if($actual_period != $current_period){
            echo 'od '.$period->created_date.' do '.$period->end_date;
        } else {
            echo 'BIEŻĄCY';
        }
    }

}
