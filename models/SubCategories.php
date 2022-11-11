<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "subCategories".
 *
 * @property int $id
 * @property string $label_ar
 * @property string $label_en
 * @property int $country_id
 * @property int $category_id
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 */

class SubCategories extends ActiveRecord{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;


    public static function tableName()
    {
        return 'subCategories';
    }

    public function rules()
    {
        return [
            [['label_ar', 'label_en', 'country_id', 'category_id', 'created_by'], 'required'],
            [['country_id', 'category_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['label_ar', 'label_en'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label_ar' => Yii::t('app', 'Label Ar'),
            'label_en' => Yii::t('app', 'Label En'),
            'country_id' => Yii::t('app', 'Country ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    const CACHE_KEY_SUBCAT= 'subCategories';




   /*public static function getSubCategories($countryId,$useCache=true):array
    {
        // TODO: redis key => CAT_COUNTRY
        if($useCache){
            $subCategories=Yii::$app->redis->get(self::CACHE_KEY_SUBCAT.'.'.$countryId);
            if($subCategories)return unserialize($subCategories);
        }
        $subCategories= self::find()->where(['country_id'=>$countryId,'status'=>self::STATUS_ACTIVE])->all();

        Yii::$app->redis->set(self::CACHE_KEY_SUBCAT.'.'.$countryId, serialize($subCategories));
        return $subCategories;

    }*/




    public static function getSubCategories($countryId,$categoryId,$useCache=true):array
    {
        // TODO: redis key => CAT_COUNTRY
        if($useCache){
            $subCategories=Yii::$app->redis->get(self::CACHE_KEY_SUBCAT.'.'.$countryId.'.'.$categoryId);
            if($subCategories)return unserialize($subCategories);
        }
        $subCategories= self::find()->where(['country_id'=>$countryId,'category_id'=>$categoryId,'status'=>self::STATUS_ACTIVE])->all();
        Yii::$app->redis->set(self::CACHE_KEY_SUBCAT.'.'.$countryId.'.'.$categoryId,serialize($subCategories));
        return $subCategories;

    }



   /* public static function getSubCategories($countryId,$useCache=true):array
    {
        // TODO: redis key => CAT_COUNTRY
        if($useCache){
            $subCategories=Yii::$app->redis->get(self::CACHE_KEY_SUBCAT.'.'.$countryId);
            if($subCategories)return unserialize($subCategories);
        }
        $subCategories= Category::getsubByCat($countryId)->all();
        Yii::$app->redis->set(self::CACHE_KEY_SUBCAT.'.'.$countryId, serialize($subCategories));
        return $subCategories;

    }*/


}