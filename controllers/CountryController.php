<?php

namespace app\controllers;


use app\models\Country;
use yii\web\Controller;

class CountryController extends Controller
{

    public function actionGet()
    {
        $countries = new Country();
        $countries = $countries->getCountry();
        $countriesModels = Country::find()->all();
        return $this->render('country', ['countries' => $countriesModels]);
    }






}