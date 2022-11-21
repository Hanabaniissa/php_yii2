<?php

namespace app\controllers;


use app\models\Assign;
use yii\web\Controller;


class AssignController extends Controller
{

    public function actionGetFields()
    {
        \Yii::$app->response->format = 'json';
        return Assign::getAssignWithFieldQuery(1)->all();
    }


}
