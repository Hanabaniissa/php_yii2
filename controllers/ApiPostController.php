<?php

namespace app\controllers;

use app\models\post;
use yii\rest\ActiveController;

class ApiPostController extends ActiveController
{

    public $modelClass = post::class;

}