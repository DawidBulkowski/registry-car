<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Settings;
use yii\data\Pagination;

class SettingsController extends Controller
{

    public const MIN_PRICE_FUEL = 'min_price_fuel';
    public const MAX_PRICE_FUEL = 'max_price_fuel';
    public const VAT_FUEL = 'vat_fuel';
    public const VAT_PHONE = 'vat_phone';
    public const OU_NUMBER = 'period_average';
    public const PERIOD_CLOSE = 'period_date_closed';
    public const TRACE_CODE = 'trace_code';
    public const PHONE_CODE = 'phone_code';
    public const MARZA = 'margin';
    public const SCOPE_PRICE = 'scope';

    public static function getValue($operation){
        $settings = Settings::find()->where(['unkey' => $operation])->one();
        return $settings->value;
    }

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_ADMIN)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $settings = Settings::find()->all();

        return $this->render('index', [
            'settings' => $settings
        ]);

    }

    public function actionEdit($id){

        $id = intval($id);

        $setting = Settings::find()->where(['id' => $id])->one();
        if(!$setting) throw new CHttpException(404,'Not Found');
        $setting_prev = $setting->getFullObjJson();

        if (Yii::$app->request->isPost){

            $setting->value = Yii::$app->request->post('value');
            $setting->save(false);

            LogController::addLog(LogController::EDIT_OPERATION, $setting->getNameObj(), $setting->getFullObjJson(), $setting_prev);
            $this->redirect('/settings/index');

        }

        return $this->render('edit', ['edit' => $setting]);

    }

}
