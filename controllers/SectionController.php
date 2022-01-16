<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Section;
use yii\data\Pagination;

class SectionController extends Controller
{

    public function beforeAction($action){

        if(!SiteController::accessPermission(SiteController::ACCESS_ADMIN)) return $this->redirect(array('site/login', 'redirect'=> $_SERVER['REQUEST_URI'] ))->send();
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);

    }

    public function actionIndex(){

        $pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 50;

        $sections = Section::find();
        $count = $sections->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $sections = $sections->offset($pagination->offset)
          ->limit($pagination->limit)
          ->all();

        return $this->render('index', [
            'sections' => $sections,
            'pagination' => $pagination,
            'pageSize' => $pageSize
        ]);

    }

    public function actionAdd(){

        $error = '';

        if (Yii::$app->request->isPost){

            $name = Yii::$app->request->post('name');
            $description = Yii::$app->request->post('description');

            $section = new Section;
            $section->name = $name;
            $section->description = $description;
            $section->save(false);

            LogController::addLog(LogController::ADD_OPERATION, $section->getNameObj(), $section->getFullObjJson());

            $this->redirect('/section/index');

        }

        return $this->render('add');

    }

    public function actionEdit($id){

        $id = intval($id);

        $section = Section::find()->where(['id' => $id])->one();
        if(!$section) throw new CHttpException(404,'Not Found');
        $section_prev = $section->getFullObjJson();

        if (Yii::$app->request->isPost){

            $section->name = Yii::$app->request->post('name');
            $section->description = Yii::$app->request->post('description');

            $section->save(false);
            LogController::addLog(LogController::EDIT_OPERATION, $section->getNameObj(), $section->getFullObjJson(), $section_prev);
            $this->redirect('/section/index');

        }

        return $this->render('edit', ['edit' => $section]);

    }

    public function actionRemove($id){

        $id = intval($id);
        $section = Section::find()->where(['id' => $id])->one();
        if($section) {
            LogController::addLog(LogController::DELETE_OPERATION, $section->getNameObj(), $section->getFullObjJson());
            $section->delete();
        }
        $this->redirect('/section/index');

    }

    public function actionCsv(){

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="dzialy-' . date('Y-m-d') .'.csv"');
        header("Content-Transfer-Encoding: UTF-8");
        header('Pragma: no-cache');

        $sections = Section::find()->all();

        echo Section::instance()->getAttributeLabel("id").";".
          Section::instance()->getAttributeLabel("name").";".
          Section::instance()->getAttributeLabel("description")." \r\n";

        foreach ($sections as $section){
            echo "$section->id;$section->name;$section->description\r\n";
        }

        LogController::addLog(LogController::RAPORT." CSV Działy");

    }


}
