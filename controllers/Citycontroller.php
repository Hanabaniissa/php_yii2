<?php

namespace app\controllers;

use app\models\City;
use yii\web\Controller;

class CityController extends Controller
{

    public function actionGet()
    {
        dd(City::getCities(1, true));
    }


}