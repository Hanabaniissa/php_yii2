<?php

namespace app\models;


use yii;
use yii\db\ActiveRecord;


/** @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $phone
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property string $category_id
 * @property string $post_image
 * @property int|null $status
 */
class post extends ActiveRecord
{


    public static function tableName()
    {
        return 'posts';
    }

    public function rules()
    {
        return [
            [['title', 'description', 'phone', 'category_id'], 'required'],
            ['user_id', 'default', 'value' => \Yii::$app->user->id],
            ['phone', 'integer'],
            [['title', 'description'], 'string', 'max' => 300],
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            ['post_image', 'string', 'max' => 255],
            [['post_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],

        ];
    }

    public function behaviors()
    {
        return [

            [
                'class' => yii\behaviors\BlameableBehavior::class,

            ]

        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'phone' => Yii::t('app', 'Phone'),
            'user_id' => Yii::t('app', 'User ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'post_image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status')
        ];
    }


    public static function findPostByCategoryIdQuery($id)
    {
        // all => []
        // one
        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'post_image'])
            ->where(['category_id' => $id, 'status' => 10]);
    }

    public static function findOne($id)
    {
        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'created_by', 'user_id', 'post_image'])
            ->where(['id' => $id])
            ->one();
    }

    public static function findMyPostQuery()
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }
        $userid = Yii::$app->user->getId();

        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'created_by', 'user_id', 'post_image'])
            ->where(['user_id' => $userid, 'status' => 10]);
    }

    const ACTIVE = 'active';
    const DELETED = 'deleted';

    public function delete()
    {
        $this->status = self::DELETED;
        $this->save(false);
    }


    public static function search($term){


        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'post_image'])
            ->orWhere('MATCH(title, description) AGAINST(:term)', ['term' => $term])
            ->params(['term' => $term]);

    }

}
