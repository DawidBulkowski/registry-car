<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\TypeCar;
use app\models\Employee;
use yii\data\Pagination;
use yii\helpers\Json;

class TypecarController extends Controller
{

    public function beforeAction($action){

          if(!SiteController::accessPermission(SiteController::ACCESS_ADMIN)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
          $this->enableCsrfValidation = false;
          return parent::beforeAction($action);

    }

    public function actionIndex(){

        $typesCar = TypeCar::find()->all();

        return $this->render('index', [
            'typesCar' => $typesCar
        ]);

    }

    public function actionAdd(){


        if (Yii::$app->request->isPost){

            $name = Yii::$app->request->post('name');
            $active = Yii::$app->request->post('active');

            $typeCar = new TypeCar;
            $typeCar->name = $name;
            $typeCar->active = $active;
            $typeCar->save(false);

            LogController::addLog(LogController::ADD_OPERATION, $typeCar->getNameObj(), $typeCar->getFullObjJson());
            $this->redirect('/typecar/index');

        }

        return $this->render('add');

    }

    public function actionEdit($id){

        $id = intval($id);
        $error = '';

        $typeCar = TypeCar::find()->where(['id' => $id])->one();
        if(!$typeCar) throw new CHttpException(404,'Not Found');
        $typeCar_prev = $typeCar->getFullObjJson();

        if (Yii::$app->request->isPost){

            $typeCar->name = Yii::$app->request->post('name');
            $typeCar->active = Yii::$app->request->post('active');
            $typeCar->save(false);
            LogController::addLog(LogController::EDIT_OPERATION, $typeCar->getNameObj(), $typeCar->getFullObjJson(), $typeCar_prev);
            $this->redirect('/typecar/index');

        }

        return $this->render('edit', ['edit' => $typeCar]);

    }

    public function actionRemove($id){

        $id = intval($id);
        $typeCar = TypeCar::find()->where(['id' => $id])->one();
        if($typeCar) {
            LogController::addLog(LogController::DELETE_OPERATION, $typeCar->getNameObj(), $typeCar->getFullObjJson());
            $typeCar->delete();
        }
        $this->redirect('/typecar/index');

    }

}
