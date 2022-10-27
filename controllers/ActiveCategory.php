<?php
namespace app\controllers;


use app\models\Category;
use yii\web\Controller;

class ActiveCategoryController extends Controller{

    public function actionCategory(){
        $categories= new Category;
        $categories->getCategories();

    }




}
