<?php

namespace app\modules\api\modules\v1\controllers;

use app\modules\api\models\Country;
use app\modules\api\models\Post;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

class ActiveController extends \yii\rest\ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class];

        return $behaviors;
    }

    /**
     * @param Country|Post $model
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete']) && $model->created_by !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You don't have permission");

        }

    }


}