<?php

namespace app\modules\api\modules\v1\controllers;
use app\modules\api\models\Post;
use yii\rest\ActiveController;
use yii\rest\Controller;

class PostController extends ActiveController
{
     public $modelClass= Post::class;
}