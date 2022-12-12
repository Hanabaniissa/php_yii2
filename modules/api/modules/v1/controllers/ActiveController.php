<?php

namespace app\modules\api\modules\v1\controllers;

use app\modules\api\interfaces\AuthInterface;
use sizeg\jwt\JwtHttpBearerAuth;

class ActiveController extends \yii\rest\ActiveController
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            'class' => JwtHttpBearerAuth::class,
        ];

        return $behaviors;
    }

    /**
     * @param AuthInterface $model
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        return $model->canAccess($action, $model, $params);
    }


}