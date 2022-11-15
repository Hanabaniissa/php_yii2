<?php

namespace app\controllers;


use app\helpers\CountryUtils;
use app\models\Country;
use app\models\SubCategories;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class SubCategoriesController extends Controller
{

    public function actionGet()
    {
// add country ID
  dd( SubCategories::getSubCategories(2,3,true));



    }

    public function actionTest()
    {

        $countryId = CountryUtils::getPreferredCountry();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $categoryId = Yii::$app->request->get('category');
        if ($categoryId) {
            return SubCategories::getSubCategories($countryId, $categoryId,true);
        }
        return null;
    }


}