<?php

namespace app\models;

use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $label_ar
 * @property string $label_en
 * @property int $country_id
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 */
class City extends ActiveRecord{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return 'city';
    }

    public function rules()
    {
        return [
            [['label_ar', 'label_en', 'country_id', 'created_by'], 'required'],
            [['country_id', 'status', 'created_by', 'updated_by'], 'integer'],
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
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }


    const CACHE_KEY_CITY= 'cities';

    public static function getCities($countryId,$useCache=true) :array{

        if ($useCache) {
            $cities = Yii::$app->redis->get(self::CACHE_KEY_CITY.'.'.$countryId);
            if ($cities) return unserialize($cities);
        }

        $cities= self::find()->where(['country_id'=>$countryId,'status'=>self::STATUS_ACTIVE])->all();
        Yii::$app->redis->set(self::CACHE_KEY_CITY.'.'.$countryId, serialize($cities));
        return $cities;
    }


}