<?php

namespace app\modules\api\controllers;

use app\modules\api\models\Country;
use yii\filters\auth\HttpBearerAuth;
use app\modules\api\modules\v1\controllers\ActiveController;
use yii\web\ForbiddenHttpException;

class CountryController extends ActiveController
{
    public $modelClass = Country::class;




//   public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
//        $behaviors['authenticator']['authMethods'] = [
//            HttpBearerAuth::class];
//
//        return $behaviors;
//    }


//    /**
//     * @param Country $model
//     * @throws ForbiddenHttpException
//     */
//    public function checkAccess($action, $model = null, $params = [])
//    {
//        if (in_array($action, ['update', 'delete']) && $model->created_by !== \Yii::$app->user->id) {
//            throw new ForbiddenHttpException("You don't have permission");
//
//        }
//
//    }


}