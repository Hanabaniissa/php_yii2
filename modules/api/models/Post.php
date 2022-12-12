<?php


namespace app\modules\api\models;

use app\modules\api\interfaces\AuthInterface;
use Yii;
use yii\web\ForbiddenHttpException;

class Post extends \app\models\post implements AuthInterface
{

    /**
     * @throws ForbiddenHttpException
     * @property self $model
     */
    public function canAccess($action, $model = null, $params = []): bool
    {
        if ($model->created_by !== Yii::$app->user->id) {
            throw new ForbiddenHttpException("You don't have permission");
        }

        return true;
    }


}