<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class BooleanIconWidget extends Widget {

    public $boolean;

    public function init(){
        parent::init();
    }

    public function run(){
        if($this->boolean == 1) echo '<i class="fa fa-check-square green-c" aria-hidden="true"></i>';
        if($this->boolean == 0) echo '<i class="fa fa-window-close red-c" aria-hidden="true"></i>';
    }

}
