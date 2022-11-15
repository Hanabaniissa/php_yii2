<?php

namespace app\controllers;

use app\helpers\CountryUtils;
use app\models\Neighborhood;
use app\models\SubCategories;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class DepDropController extends Controller
{
    public function actionGet($type)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $parents = Yii::$app->request->post('depdrop_all_params');
        $data = [];
        switch ($type) {
            case 'neighborhood':
                $cityId = $parents['city'];
                $data = Neighborhood::find()
                    ->select(['id', 'label_en as name'])
                    ->where(['city_id' => $cityId, 'status' => 1])
                    ->asArray()
                    ->all();
                break;



            case 'subcategory':
                $categoryId=$parents['category'];
                $countryId = CountryUtils::getPreferredCountry();
                $subCategories = SubCategories::getSubCategories($countryId,$categoryId,true);
                foreach ($subCategories as $category){
                    $data[] = [
                        'id' => $category->id,
                        'name' => $category->label_en,
                    ];
                }

                break;
        }
        return ['output' => $data, 'selected' => ''];
    }
}