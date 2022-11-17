<?php

namespace api\modules\v1\controllers;

use yii\rest\Controller;

class PostController extends Controller
{
    public function behaviors()
    {
        return [];
    }

    public function actionIndex() {
        die("Hello world!");
    }
}