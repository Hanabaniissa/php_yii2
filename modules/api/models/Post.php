<?php


namespace app\modules\api\models;

use app\modules\api\interfaces\AuthInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\web\ForbiddenHttpException;

class Post extends \app\models\post implements AuthInterface
{

    /**
     * @throws ForbiddenHttpException
     **@property self $model
     */

    public function canAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete']) && $model->created_by !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You don't have permission");

        }
    }


//    public function behaviors()
//    {
//        return [
//            [
//                'class' => TimestampBehavior::class,
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
//                ],
//                'value' => date('Y-m-d H:i:s'),
//            ],
//            [
//                'class' => \yii\behaviors\BlameableBehavior::className(),
//                'value' => Yii::$app->user->id,
//                'updatedByAttribute' => null,
//            ],
//
//        ];
//    }




}