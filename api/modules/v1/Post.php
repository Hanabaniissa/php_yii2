<?php
namespace api\modules\v1;

use yii\behaviors\TimestampBehavior;
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
 * @property int $country_id
 * @property int $city_id
 * @property int $subCategory_id
 * @property int $neighborhood_id
 * @property int|null $price
 * @property int $updated_by [int]
*/

class Post extends ActiveRecord{


    public static function tableName()
    {
        return "posts";
    }

    public function rules()
    {
        return [
            [['title', 'description', 'phone', 'user_id', 'category_id', 'created_by', 'post_image', 'country_id', 'city_id', 'subCategory_id', 'neighborhood_id'], 'required'],
            [['phone', 'user_id', 'category_id', 'created_by', 'updated_by', 'status', 'country_id', 'city_id', 'subCategory_id', 'neighborhood_id', 'price'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'description', 'post_image'], 'string', 'max' => 255],
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
            'post_image' => 'Post Image',
            'status' => 'Status',
            'country_id' => 'Country ID',
            'city_id' => 'City ID',
            'subCategory_id' => 'Sub Category ID',
            'neighborhood_id' => 'Neighborhood ID',
            'price' => 'Price',
        ];
    }

    public function behaviors()
    {
        return [

            [
                TimestampBehavior::class,

            ]

        ];
    }


}