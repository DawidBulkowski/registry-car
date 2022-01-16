<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class TypeUserListWidget extends Widget {

    public $access;

    public function init(){
        parent::init();
    }

    public function run(){
        if($this->access == 0) echo '<span class="badge badge-secondary">Kierowca</span>';
        if($this->access == 1) echo '<span class="badge badge-primary">Manager</span>';
        if($this->access == 2) echo '<span class="badge badge-success">Administrator</span>';
    }

}
