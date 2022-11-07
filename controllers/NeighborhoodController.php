<?php

namespace app\controllers;


use app\models\Country;
use app\models\Neighborhood;
use app\models\SubCategories;
use yii\web\Controller;

class NeighborhoodController extends Controller
{

    public function actionGet()
    {
        $neighborhood = new Neighborhood();
        $neighborhood = $neighborhood->getNeighborhood();

    }


}