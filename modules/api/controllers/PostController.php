<?php

namespace app\modules\api\controllers;

use app\modules\api\models\Post;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class PostController extends ActiveController
{
    public $modelClass = Post::class;


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class];

        return $behaviors;
    }

}