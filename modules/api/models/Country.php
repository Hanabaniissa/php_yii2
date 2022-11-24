<?php

namespace app\modules\api\models;

use app\modules\api\interfaces\AuthInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\web\ForbiddenHttpException;

class Country extends \app\models\Country
{


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
//                // 'value' => Yii::$app->user->id,
//                'updatedByAttribute' => null,
//            ],
//
//        ];
//    }


}