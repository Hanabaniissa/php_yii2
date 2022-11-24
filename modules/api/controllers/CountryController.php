<?php

namespace app\modules\api\controllers;

use app\modules\api\interfaces\AuthInterface;
use app\modules\api\models\Country;
use app\modules\api\models\Post;
use yii\filters\auth\HttpBearerAuth;
use app\modules\api\modules\v1\controllers\ActiveController;
use yii\web\ForbiddenHttpException;
use function Symfony\Component\String\u;

class CountryController extends ActiveController implements AuthInterface
{
    public $modelClass = Country::class;


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']['checkAccess'], $actions['index']['checkAccess'], $actions['view']['checkAccess']);
        return $actions;

    }


    /**
     * @throws ForbiddenHttpException
     * @property Country $model
     */

    public function canAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete']) && $model->created_by !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You don't have permission");

        }
    }
}