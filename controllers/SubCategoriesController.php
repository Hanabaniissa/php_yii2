<?php

namespace app\controllers;


use app\helpers\CountryUtils;
use app\models\Country;
use app\models\SubCategories;
use Yii;
use yii\web\Controller;

class SubCategoriesController extends Controller
{

    public function actionGet()
    {
// add country ID
    return SubCategories::getSubCategories(1,1,true);



    }

    public function actionTest()
    {
        $countryId = CountryUtils::getPreferredCountry();
        Yii::$app->response->format = 'json';
        $categoryId = Yii::$app->request->get('category');
        if ($categoryId) {
            return SubCategories::getSubCategories($countryId, $categoryId);
        }
        return null;
    }


}