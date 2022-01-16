<?php

namespace app\models;

use app\models\Employee;
use app\models\TypeCar;

class Car extends \yii\db\ActiveRecord {

      public $averageBOR;
      public $averageOU;
      public $distanceBOR;
      public $averagePriceFuelBOR;

      public $refuelAmount;
      public $refuelPrice;

      public function attributeLabels(){
          return [
              'id' => 'ID',
              'description' => 'Opis',
              'register' => 'Numer rejestracyjny',
              'employee_id' => 'Pracownik',
              'section_id' => 'Dział',
              'symbol' => 'Symbol',
              'fuel' => 'Rodzaj paliwa',
              'type' => 'Typ',
              'norm' => 'Norma emisji spalin',
              'used' => 'Czy użytkowany?'
          ];
      }

      public function getEmployee(){
          $employee = Employee::find()->where(['id' => $this->employee_id])->one();
          return $employee->surname." ".$employee->name;
      }

      public function getSection(){
          $section = Section::find()->where(['id' => $this->section_id])->one();
          return $section->name;
      }

      public function getUsed(){
          if($this->used == 0) return "Nie użytkowany";
          if($this->used == 1) return "Użytkowany";
      }

      public function getType(){
          $type = TypeCar::find()->where(['id' => $this->type_id])->one();
          return $type->name;
      }

      public function getFullName(){
          return $this->register." ".$this->fuel." (".$this->type.")";
      }

      public function getNameObj(){
          return "SAMOCHÓD ".$this->register;
      }

      public function getFullObjJson(){
          $labels = $this->attributeLabels();
          $data = [
            $labels['id'] => $this->id,
            $labels['symbol'] => $this->symbol,
            $labels['description'] => $this->description,
            $labels['register'] => $this->register,
            $labels['employee_id'] => $this->getEmployee(),
            $labels['section_id'] => $this->getSection(),
            $labels['fuel'] => $this->fuel,
            $labels['type'] => $this->getType(),
            $labels['norm'] => $this->norm,
            $labels['used'] => $this->getUsed(),
          ];
          return json_encode($data);
      }

}
