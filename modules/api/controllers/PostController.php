<?php

namespace app\modules\api\controllers;

use app\modules\api\models\Post;
use yii\rest\ActiveController;

class PostController extends ActiveController
{
    public $modelClass=Post::class;



}