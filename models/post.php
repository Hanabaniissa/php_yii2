<?php

namespace app\models;


use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Query;
use yii\helpers\Json;


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
 * @property-read PostValue[] $value
 * @property-read Category[] $category
 * @property-read SubCategories[] $subCat
 * @property-read City[] $city
 * @property-read Neighborhood[] $neighborhood
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
            [['title', 'description', 'phone', 'category_id', 'country_id', 'subCategory_id', 'city_id', 'neighborhood_id', 'price'], 'required'],
            ['user_id', 'default', 'value' => \Yii::$app->user->id],
            [['status', 'created_by', 'updated_by', 'phone'], 'integer'],

            [['title', 'description'], 'string', 'max' => 300],
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            ['post_image', 'string', 'max' => 255],
            [['post_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],

        ];
    }

//    public static function getPostKeys($id){
//        $attributes['id']=$id;
//
//        $posts=self::find()->where(['id'=>$id])->one();
//        foreach($posts as $post=>$value){
//            $key=$post;
//            $key_value=$value;;
//            $type=gettype($value);
//            $field=str_replace('','_',strtolower($key));
//            $field.="_".self::getPostFieldTypeForSolr($type);
//
//            $attributes[$field]=$key_value;
//            echo Json::encode($attributes);die;
//
//
//        }
//
//        return $attributes;
//    }


//    private static function getPostFieldTypeForSolr($field): string
//    {
//        switch($field){
//            case 'integer': return 'i';
//                break;
//            case 'string': return 's';
//                break;
//            default: return 'null';
//        }}

    public function afterSave($insert, $changedAttributes)
    {
        $post = $this;
        $id = $post->id;
        echo '<pre>';

//        $temp_post= [
//                'id_i' => $post->id,
//                'title_s' => $post->title,
//                'desc_s' => $post->description,
//                'phone_i'=>$post->phone,
//                'user_id_i'=>$post->user_id,
//                'category_id_i'=>$post->category_id,
//                'created_at_s'=>$post->created_at,
//                'created_by_i'=>$post->created_by,
//                'updated_at_s'=>$post->updated_at,
//                'updated_by_i'=>$post->updated_by,
//                'post_image_s'=>$post->post_image,
//                'status_i'=>$post->status,
//                'city_id_i'=>$post->city_id,
//                'subCategory_id_i'=>$post->subCategory_id,
//                'neighborhood_id_i'=>$post->neighborhood_id,
//                'price_i'=>$post->price,
//            ];

        $temp_post[] = Solr::getPostKeys($id);
        $posts_json = Json::encode($temp_post);

        $url = "http://localhost:8983/solr/test_dynamic/update/json/docs?commit=true";
        $ch = curl_init();
        $header = array('Content-Type: application/json');

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts_json);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

        $data = curl_exec($ch);
        $return = 0;
        if (!curl_errno($ch)) {
            $return = $data;
        }
        curl_close($ch);
        return $return;

    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],

            [
                'class' => yii\behaviors\BlameableBehavior::class,

            ],

        ];
    }

    public function attributeLabels()
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


    public static function findPostByCategoryIdQuery($id)
    {
        // all => []
        // one
        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'post_image'])
            ->where(['category_id' => $id, 'status' => 10])
            ->orderBy(['id' => SORT_DESC]);
    }


    /*public static function findOne($id)
    {

        return (new Query())->select('*')
            ->from(self::tableName())
            ->innerJoin(PostValue::tableName(), 'post_value.post_id = posts.id')
            ->where(['posts.id'=>$id])
            ->one();
    }
*/

    /**
     * @return ActiveQuery
     */
    public function getValue(): yii\db\ActiveQuery
    {
        return $this->hasMany(PostValue::class, ['post_id' => 'id']);
    }

    public function getCategory(): yii\db\ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getSubCat(): yii\db\ActiveQuery
    {
        return $this->hasOne(SubCategories::class, ['id' => 'subCategory_id']);
    }

    public function getCity(): yii\db\ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    public function getNeighborhood(): yii\db\ActiveQuery
    {
        return $this->hasOne(Neighborhood::class, ['id' => 'neighborhood_id']);
    }

    public static function findOne($id)
    {
        return self::find()
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
            ->where(['user_id' => $userid, 'status' => 10])
            ->orderBy(['id' => SORT_DESC]);
    }

    const ACTIVE = 'active';
    const DELETED = 'deleted';

    public function delete()
    {
        $this->status = self::DELETED;
        $this->save(false);
        $post = $this;
        $temp_post = [
            'id_i' => $post->id,
            'status_s' => $post->status
        ];
        $posts_json = \yii\helpers\Json::encode($temp_post);
        $url = "http://localhost:8983/solr/test_dynamic/update/json/docs?commit=true";
        $ch = curl_init();
        $header = array('Content-Type: application/json');
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts_json);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

        $data = curl_exec($ch);
        $return = 0;
        if (!curl_errno($ch)) {
            $return = $data;
        }
        curl_close($ch);
        return $return;

    }


    public static function search($term)
    {


        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'post_image'])
            ->orWhere('MATCH(title, description) AGAINST(:term)', ['term' => $term])
            ->params(['term' => $term]);

    }

}
