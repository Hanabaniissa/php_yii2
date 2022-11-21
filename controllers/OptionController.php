<?php

namespace app\controllers;


use app\models\Option;
use Yii;
use yii\web\Controller;


class OptionController extends Controller
{

    public function actionGet()
    {
        \Yii::$app->response->format = 'json';
        $fieldId = Yii::$app->request->get('field_id');
        if ($fieldId) {
            return Option::getOptionByFieldQuery($fieldId)->all();
        }
        return null;
    }


}
