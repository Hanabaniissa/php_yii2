<?php

namespace api\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex(): string
    {
        return "We're proud to introduce AbuyZ API.";
    }
}