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
 */
class post extends ActiveRecord
{

    public $fileImage;


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
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            ['post_image', 'string','max'=>255],
            [['fileImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],

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
            'post_image' => Yii::t('app', 'Image'),
        ];
    }


    public static function findPostByCategoryId($id)
    {
        // all => []
        // one
        return self::find()
            ->select(['title', 'description', 'phone', 'id','created_at', 'post_image'])
            ->where(['category_id' => $id])
            ->all();
    }

    public static function findOnePost($id)
    {
        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'created_by', 'user_id', 'post_image'])
            ->where(['id' => $id])
            ->one();
    }


    public function upload(){
        if (true) {
            $path = $this->uploadPath() . $this->id . "." .$this->fileImage->extension;
            $this->fileImage->saveAs($path);
            $this->post_image = $this->id . "." .$this->fileImage->extension;
            $this->save();
            return true;
        } else {
            return false;
        }
    }

    public function uploadPath() {
        return yii\helpers\Url::to('@web/upload');

    }
}
