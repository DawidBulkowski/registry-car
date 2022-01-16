<?php

namespace app\models;

class Log extends \yii\db\ActiveRecord {

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'created' => 'Data utworzenia',
            'value' => 'Opis',
        ];
    }

}
