<?php
namespace app\controllers;


use app\models\Assign;
use app\models\Category;
use yii\web\Controller;


class AssignController extends Controller{

    public function actionGet(){
        \Yii::$app->response->format='json';
      return  Assign::getAssignWithField()->all();



    }




}
