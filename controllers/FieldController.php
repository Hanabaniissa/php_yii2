<?php

namespace app\controllers;


use app\models\Assign;
use app\models\Option;
use yii\web\Controller;


class FieldController extends Controller
{

    public function actionGet()
    {
        $subCatId = \Yii::$app->request->get('subCategory_id');
        if ($subCatId) {
            $assignModels = Assign::getAssignWithFieldQuery($subCatId)->all();
            return $this->renderAjax('fields', ['assigns' => $assignModels]);
        }
        return null;
    }


}
