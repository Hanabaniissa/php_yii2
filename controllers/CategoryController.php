<?php
namespace app\controllers;


use app\models\Category;
use yii\web\Controller;

class CategoryController extends Controller{

    public function actionGet(){

      // dd( $category=Category::find()->where(['country_id' => 2])->all());


    }

    public function actionTest(){
        dd(Category::getCategoriesBy(2,true));




    }



}
