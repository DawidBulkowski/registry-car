<?php

namespace app\models;

use app\models\Employee;
use app\models\Car;

class Trace extends \yii\db\ActiveRecord {

      public function attributeLabels(){
          return [
              'id' => 'ID',
              'car_id' => 'Samochód',
              'employee_id' => 'Pracownik',
              'description' => 'Opis',
              'start_date' => 'Data wyjazdu',
              'end_date' => 'Data powrotu',
              'km_corp' => 'Kilometry służbowe',
              'km_priv' => 'Kilometry prywatne',
              'km_final' => 'Stan licznika',
              'period_id' => 'Okres rozliczeniowy'
          ];
      }

      public function getEmployee(){
          $employee = Employee::find()->where(['id' => $this->employee_id])->one();
          return $employee->getFullName();
      }

      public function getCar(){
          $car = Car::find()->where(['id' => $this->car_id])->one();
          return $car->register;
      }

      public function getNameObj(){
          return "WYJAZD [".$this->getCar()." kierowca ".$this->getEmployee()."] ".date('d/m/Y H:i', $this->start_date);
      }

      public function getFullObjJson(){
          $labels = $this->attributeLabels();
          $data = [
            $labels['id'] => $this->id,
            $labels['car_id'] => $this->getCar(),
            $labels['employee_id'] => $this->getEmployee(),
            $labels['description'] => $this->description,
            $labels['start_date'] => $this->start_date,
            $labels['end_date'] => $this->end_date,
            $labels['km_corp'] => $this->km_corp,
            $labels['km_priv'] => $this->km_priv,
            $labels['km_final'] => $this->km_final,
          ];
          return json_encode($data);
      }

}
