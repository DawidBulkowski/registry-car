<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use app\controllers\PeriodController;

class Employee extends \yii\db\ActiveRecord implements IdentityInterface {

    public const TYPE = ['Kierowca', 'Menadżer', 'Administrator'];

    public $privateDistance;
    public $privateFuelPrice;

    public $listOfCars;

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => 'Imię',
            'surname' => 'Nazwisko',
            'login' => 'Login',
            'passwo' => 'Hasło',
            'kontrahent' => 'Kontrahent PIFIRMA',
            'access' => 'Rodzaj',
            'works' => 'Czy pracuje'
        ];
    }

    public function getTypeEmployee(){
        if($this->access == 0) return "Kierowca";
        if($this->access == 1) return "Menadżer";
        if($this->access == 2) return "Administrator";
    }

    public function getWorksEmployee(){
        if($this->works == 0) return "Nie pracuje";
        if($this->works == 1) return "Pracuje";
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return '';
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }

    public function getAccess(){
        return $this->access;
    }

    public function login($password){
        return md5($password) == $this->passwo;
    }

    public function loginLdap($password){
        $domain = Yii::$app->params['domainActiveDirectory'];
        $ldap = @ldap_connect($domain);
        if (@ldap_bind($ldap, $this->login."@".$domain, $password)) return true;
        return false;
    }

    public function getFullName(){
        return $this->surname." ".$this->name." - ".strtoupper($this::TYPE[$this->access]);
    }

    public function getFullNameToLog(){
        return "PRACOWNIK [".strtoupper($this::TYPE[$this->access])." ".$this->surname." ".$this->name."]";
    }

    public function getNameObj(){
        return $this->getFullNameToLog();
    }

    public function getFullObjJson(){
        $labels = $this->attributeLabels();
        $data = [
          $labels['id'] => $this->id,
          $labels['name'] => $this->name,
          $labels['surname'] => $this->surname,
          $labels['kontrahent'] => $this->kontrahent,
          $labels['login'] => $this->login,
          $labels['access'] => $this->access,
          $labels['works'] => $this->works,
        ];
        return json_encode($data);
    }

}
