<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use app\controllers\PeriodController;

class AlertActualPeriod extends Widget {

    public function init(){
        parent::init();
    }

    public function run(){
        $actual_period = PeriodController::getActualId();
        $current_period = isset($_SESSION['period']) ? $_SESSION['period'] : $actual_period;
        if($actual_period != $current_period){
            echo '<div class="alert alert-danger" role="alert" style="margin: 0; text-align: center; ">
              Wybrany okres rozliczeniowy jest już zamknięty. <a href="/period/change?id='.$actual_period.'"><button class="btn btn-sm btn-primary">Przełącz na aktualny</button></a>
            </div>';
        }
    }

}
