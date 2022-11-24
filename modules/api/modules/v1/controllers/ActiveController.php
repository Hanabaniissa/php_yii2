<?php

namespace app\modules\api\modules\v1\controllers;

use app\modules\api\interfaces\AuthInterface;
use app\modules\api\models\Country;
use app\modules\api\models\Post;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\web\ForbiddenHttpException;

class ActiveController extends \yii\rest\ActiveController
{

    public function behaviors()
    {
//        $headers = \Yii::$app->request->headers;
//        $jwtToken = $headers['x-api-key'];
//        $jwtObj = \Yii::$app->jwt;

        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only']=['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            'class' => JwtHttpBearerAuth::class,
//            'optional' => [
//                'login',
//            ]
        ];

        return $behaviors;
    }

//    /**
//     * @param AuthInterface $model
//     * @throws ForbiddenHttpException
//     */
//    public function checkAccess($action, $model = null, $params = [])
//    {
//        return $model->canAccess($action, $model = null, $params = []);
//    }



    /**
     * @throws ForbiddenHttpException
     **@property Country|Post $model
     */

    public function checkAccess($action, $model = null, $params = [])
    {

        if (in_array($action, ['update', 'delete']) && $model->created_by !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You don't have permission");

        }
    }


}