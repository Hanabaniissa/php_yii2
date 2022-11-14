<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property int|null $status
 * @property string|null $created_at
 */
class Favorite extends ActiveRecord
{
    const ACTIVE_STATUS = 1;
    const DISABLED_STATUS = 0;

    public static function tableName()
    {
        return 'favorite';
    }

    public function rules()
    {
        return [
            [['user_id', 'post_id'], 'required'],
            [['user_id', 'post_id', 'status'], 'integer'],
            [['status'], 'default', 'value' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

//    public function getPost()
//    {
//        return $this->hasOne(post::class, ['id' => 'post_id']);
//    }

    public static function getFavorite()
    {
        $userId = Yii::$app->user->id;
        return self::find()
            ->where(['user_id' => $userId, 'status' =>self::ACTIVE_STATUS])
            ->all();

    }


    public static function getFavoritePostQuery()
    {
        $userId = Yii::$app->user->id;
        return (new Query())
            ->select('*')
            ->from(self::tableName())
            ->innerJoin(post::tableName(), 'favorite.post_id = posts.id')
            ->where(['favorite.user_id' => $userId,
                'favorite.status' => self::ACTIVE_STATUS,
                'posts.status' => 10])
            ->orderBy(['favorite.id' => SORT_DESC]);
    }
}
