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
            [['title', 'description'], 'string', 'max' => 255],
            ['created_by', 'default', 'value' => \Yii::$app->user->id]

        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'phone' => 'Phone',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }


    public static function findPostByCategoryId($id)
    {
        // all => []
        // one
        return self::find()
            ->select(['title', 'description', 'phone', 'id'])
            ->where(['category_id' => $id])
            ->all();
    }

}
