<?php

namespace app\controllers;

use React\EventLoop\Factory;
use yii\web\Controller;

class ThreadController extends Controller
{
    public function actionThread()
    {


        return $this->render('thread');    }
}