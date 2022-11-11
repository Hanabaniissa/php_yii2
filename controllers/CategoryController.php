<?php
namespace app\controllers;


use app\models\Category;
use yii\web\Controller;

class CategoryController extends Controller{

    public function actionCategory(){
        $categories= new Category;
        $categories->getCategories(3,true);

    }

    public function actionTest(){
        \Yii::$app->response->format='json';
        return Category::getCategoriesBy(1,true);




    }



}
