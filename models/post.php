<?php

namespace app\models;


use app\components\solr\Documents;
use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;


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




//    public function afterSave($insert, $changedAttributes)
//    {
//       $post= $this;
//       $id=$post->id;
//
////        $attributes['id'] = $id;
//        $postFields = [
//            self::class => $id,
//            'app\models\Country' => $post->country_id,
//            'app\models\City' => $post->city_id,
//            'app\models\Neighborhood' => $post->neighborhood_id,
//            'app\models\Category' => $post->category_id,
//            'app\models\SubCategories' => $post->subCategory_id,
//
//        ];
//
//        $temp_post['id']=$id;
//
//        foreach ($postFields as $model=>$id_model){
//            $modelName = str_replace('app\models\\', '', strtolower($model));
//
//            $temp_post[$modelName]=Solr::getPostKeys($model,$id_model,$modelName);
//        }
//
////        $modelName = str_replace('app\models\\', '', strtolower(self::class));
////
////        $dataConfigParams = [
////            'id' => $id,
////            'model' => self::class,
////            'core' => $core,
////            'modelName' => $modelName,
////        ];
////         Documents::create($dataConfigParams);
//
//
//
//        $posts_json = Json::encode($temp_post);
//
//        $url = "http://localhost:8983/solr/test_dynamic/update/json/docs?commit=true";
//        $ch = curl_init();
//        $header = array('Content-Type: application/json');
//
//        curl_setopt($ch, CURLOPT_URL, $url);
//
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//        curl_setopt($ch, CURLOPT_POST, 1);
//
//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts_json);
//
//        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//
//        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
//
//        $data = curl_exec($ch);
//        $return = 0;
//        if (!curl_errno($ch)) {
//            $return = $data;
//        }
//        curl_close($ch);
//        return $return;
//
//    }


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

        $data = [
            'id' => $post->id,
            'status_post_i' => 0,
        ];

        $core='test_dynamic';
        Documents::delete($data,$core);

//        $posts_json = \yii\helpers\Json::encode($temp_post);

//        $url = "http://localhost:8983/solr/can/update/json/docs?commit=true";
//        $ch = curl_init();
//        $header = array('Content-Type: application/json');
//        curl_setopt($ch, CURLOPT_URL, $url);
//
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//        curl_setopt($ch, CURLOPT_POST, 1);
//
//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts_json);
//
//        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//
//        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
//
//        $data = curl_exec($ch);
//        $return = 0;
//        if (!curl_errno($ch)) {
//            $return = $data;
//        }
//        curl_close($ch);
//        return $return;
    }


    public static function search($term)
    {

        return self::find()
            ->select(['title', 'description', 'phone', 'id', 'created_at', 'post_image'])
            ->orWhere('MATCH(title, description) AGAINST(:term)', ['term' => $term])
            ->params(['term' => $term]);

    }

}
