<?php

namespace app\models;

class Settings extends \yii\db\ActiveRecord {

      public function attributeLabels(){
          return [
              'id' => 'ID',
              'unkey' => 'Klucz',
              'value' => 'Wartość',
              'description' => 'Opis'
          ];
      }

      public function getNameObj(){
          return "USTAWIENIE [".$this->unkey."]";
      }

      public function getFullObjJson(){
          $labels = $this->attributeLabels();
          $data = [
            $labels['id'] => $this->id,
            $labels['unkey'] => $this->unkey,
            $labels['value'] => $this->value,
            $labels['description'] => $this->description,
          ];
          return json_encode($data);
      }

}
