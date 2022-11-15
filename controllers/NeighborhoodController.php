<?php

namespace app\controllers;


use app\models\Country;
use app\models\Neighborhood;
use app\models\SubCategories;
use Psy\Util\Json;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class NeighborhoodController extends Controller
{

    public function actionGet()
    {


        Yii::$app->response->format = Response::FORMAT_JSON;

         $cityId=\Yii::$app->request->get('cityId');
         if($cityId){
             return Neighborhood::find()->where(['city_id'=>$cityId,'status'=>1])->all();

         }
         return null;


    }


   /* public function actionTest()
    {
        $countryId = CountryUtils::getPreferredCountry();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $categoryId = Yii::$app->request->get('category');
        if ($categoryId) {
            return SubCategories::getSubCategories($countryId, $categoryId,true);
        }
        return null;
    }
*/

}