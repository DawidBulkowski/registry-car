<?php

namespace app\models;

class TypeCar extends \yii\db\ActiveRecord {

      public $total_fuel_brutto;
      public $total_product_netto;
      public $total_product_brutto;
      public $sections;

      public function attributeLabels(){
          return [
              'id' => 'ID',
              'name' => 'Nazwa'
          ];
      }

      public function getNameObj(){
          return "TYP SAMOCHODU [".$this->name."]";
      }

      public function getFullObjJson(){
          $labels = $this->attributeLabels();
          $data = [
            $labels['id'] => $this->id,
            $labels['name'] => $this->name,
          ];
          return json_encode($data);
      }

}
