<?php

namespace app\models\solr;

use Yii;
use yii\base\Model;

class Post extends Model
{
    public $id;
    public $title;
    public $description;
    public $phone;
    public $user_id;
    public $price;
    public $category_id;
    public $subcategory_id;
    public $city_id;
    public $neighborhood_id;
    public $country_id;
    public $status;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;


    public function rules(): array
    {
        return [
            ['id', 'safe'],
            [['title', 'description', 'phone', 'category_id', 'country_id', 'subCategory_id', 'city_id', 'neighborhood_id', 'price'], 'required'],
            ['user_id', 'default', 'value' => \Yii::$app->user->id],
            [['status', 'created_by', 'updated_by', 'phone'], 'integer'],

            [['title', 'description'], 'string', 'max' => 300],
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            ['post_image', 'string', 'max' => 255],
            [['post_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],

        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'phone' => Yii::t('app', 'Phone'),
            'user_id' => Yii::t('app', 'User'),
            'category_id' => Yii::t('app', 'Category '),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'post_image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'), 'country_id' => Yii::t('app', 'Country'),
            'city_id' => Yii::t('app', 'City'),
            'subCategory_id' => Yii::t('app', 'Sub Category'),
            'neighborhood_id' => Yii::t('app', 'Neighborhood'),
            'price' => Yii::t('app', 'Price')

        ];
    }

    public static function prepareArray($post): array
    {
        $attributes = [];
        unset($post['id']);
        foreach ($post as $item => $value) {
            $key = explode(".", $item)[0];
            $item = preg_replace('/^([A-z0-9]*)\./', '', $item);
            $item = preg_replace('/_([^_]*)$/', '', $item);
            $attributes[$key][$item] = $value;
//            $attributes[]=str_replace('post.','',preg_replace('_i_', '', $item));
        }
        dd($attributes);
        return $attributes;
    }

    public static function getPost($postArray): Post
    {


        $post = (array)$postArray;
        $postAttributes = self::prepareArray($post);
        $attributes = [];
        unset($post['id']);

//        $s = preg_replace('/^([a-z0-9]*)\./', '', 'post.title_s');
//        dd($s);
//
//        foreach ($post as $item=>$value){
//            $item = preg_replace('/^([a-z0-9]*)\./', '', $item);
//            $attributes[$item]=$value;
////            dd($s);
////            $attributes[]=str_replace('post.','',preg_replace('_i_', '', $item));
//        }
        dd($attributes);
        dd($post);
        $tempPost = new self();

        $tempPost->load($post, '');
//        $tmpPost->id = $post['id'];
        dd($tempPost->attributes);
        $posts[] = $tempPost;

        return $tempPost;
    }

}