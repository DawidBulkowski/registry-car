<?php

namespace app\models;

use app\models\Section;
use app\models\Car;
use app\models\Product;
use app\models\RefuelShort;

class Refuel extends \yii\db\ActiveRecord {

      public function attributeLabels(){
          return [
              'id' => 'ID',
              'created' => 'Data tankowania',
              'car_id' => 'Samochód',
              'section_id' => 'Dział',
              'fuel_price' => 'Cena litra paliwa (brutto)',
              'fuel_amount' => 'Ilość paliwa',
              'discount' => 'Rabat',
              'period_id' => 'Okres rozliczeniowy'
          ];
      }

      public function getCar(){
          $car = Car::find()->where(['id' => $this->car_id])->one();
          return $car->register;
      }

      public function getSection(){
          $section = Section::find()->where(['id' => $this->section_id])->one();
          return $section->name;
      }

      public function getNameObj(){
          return "TANKOWANIE [".date('d/m/Y', $this->created)."] ".$this->getSection().", ".$this->getCar();
      }

      public function sumProduct(){
          $sum = Product::find()->where(['refuel_id' => $this->id])->sum('price_brutto');
          return $sum ? $sum : 0;
      }

      public function getFullObjJson(){
          $labels = $this->attributeLabels();
          $data = [
            $labels['id'] => $this->id,
            $labels['created'] => $this->created,
            $labels['car_id'] => $this->getCar(),
            $labels['section_id'] => $this->getSection(),
            $labels['fuel_price'] => $this->fuel_price,
            $labels['fuel_amount'] => $this->fuel_amount,
            $labels['period_id'] => $this->period_id,
          ];
          return json_encode($data);
      }

      public function getShortObj(){

          $short = new RefuelShort();
          $short->car = $this->getCar();
          $short->fuel_price = $this->fuel_price;
          $short->fuel_amount = $this->fuel_amount;
          $short->other = $this->sumProduct();

          return $short;

      }

}
