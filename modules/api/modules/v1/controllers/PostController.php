<?php

namespace app\modules\api\modules\v1\controllers;

use app\modules\api\models\Post;
use Yii;
use yii\web\Response;

class PostController extends ActiveController
{
    public $modelClass = Post::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']['checkAccess']);
        return $actions;
    }


}