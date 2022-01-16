<?php

namespace app\models;

class Period extends \yii\db\ActiveRecord {

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'created_date' => 'Data rozpoczęcia',
            'end_date' => 'Data zakończenia',
            'completed' => 'Zamknięty',
        ];
    }

}
