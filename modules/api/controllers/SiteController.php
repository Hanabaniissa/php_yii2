<?php

namespace app\modules\api\controllers;

use app\models\User;
use yii\base\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    // JWT
    // OAuth2
    public function actionLogin()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $user = User::findOne(['username' => 'Hana']);
        return $user;
    }
}