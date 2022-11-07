<?php
namespace app\controllers;

use app\models\City;
use yii\web\Controller;

class CityController extends Controller
{

public function actionGet()
{
 $cities= new City();
dd( $cities->getCities(3, true));


}


}