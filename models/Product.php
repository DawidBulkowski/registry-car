<?php

namespace app\models;

class Product extends \yii\db\ActiveRecord {

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => 'Nazwa produktu',
            'price' => 'Cena netto',
            'price_brutto' => 'Cena brutto',
            'refuel_id' => 'Tankowanie',
        ];
    }

}
