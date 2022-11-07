<?php

namespace app\controllers;


use app\models\Country;
use app\models\SubCategories;
use yii\web\Controller;

class SubCategoriesController extends Controller
{

    public function actionGet()
    {
        $subCategories = new SubCategories();
// add country ID
     $subCategories->getSubCategories(3,true);



    }


}