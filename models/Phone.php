<?php

namespace app\models;

class Phone extends \yii\db\ActiveRecord {

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'employee_id' => 'Pracownik',
            'value' => 'Wartość',
        ];
    }

    public function getEmployee(){
        $employee = Employee::find()->where(['id' => $this->employee_id])->one();
        return $employee->getFullName();
    }

    public function getNameObj(){
        return "Telefony [".$this->getEmployee()."]";
    }

    public function getFullObjJson(){
        $labels = $this->attributeLabels();
        $data = [
          $labels['id'] => $this->id,
          $labels['employee_id'] => $this->getEmployee(),
          $labels['value'] => $this->value,
        ];
        return json_encode($data);
    }

}
