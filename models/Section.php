<?php

namespace app\models;

class Section extends \yii\db\ActiveRecord {

    public $fuel_netto;
    public $fuel_brutto;
    public $products_netto;
    public $products_brutto;

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => 'Nazwa',
            'description' => 'Opis',
        ];
    }

    public function getNameObj(){
        return "DZIAŁ [".$this->name."]";
    }

    public function getFullObjJson(){
        $labels = $this->attributeLabels();
        $data = [
          $labels['id'] => $this->id,
          $labels['name'] => $this->name,
          $labels['description'] => $this->description,
        ];
        return json_encode($data);
    }

}
