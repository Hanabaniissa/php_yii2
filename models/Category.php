<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/** @property integer $id
 * @property string $label_ar
 * @property string $label_en
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $status
 * @property int $country_id

 */
class Category extends ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['label_ar', 'label_en', 'created_by', 'country_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['created_by', 'updated_by', 'status', 'country_id'], 'integer'],
            [['label_ar', 'label_en'], 'string', 'max' => 50],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label_ar' => Yii::t('app', 'Label Ar'),
            'label_en' => Yii::t('app', 'Label En'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    const CACHE_KEY_CATEGORY = 'categories';

    /* public static function getCategories($useCache = true): array
    {
        if ($useCache) {
            $categories = Yii::$app->redis->get(self::CACHE_KEY);
            if ($categories) return unserialize($categories);
        }

        $categories = self::find()->all();
        Yii::$app->redis->set(self::CACHE_KEY, serialize($categories));
        return $categories;
    }
*/

    //If you want to update the redis data, set false then true

    public static function getCategoriesBy($countryId, $useCache = true): array
    {
        if ($useCache) {
            $categories = Yii::$app->redis->get(self::CACHE_KEY_CATEGORY.'.'.$countryId);
            if ($categories) return unserialize($categories);
        }

        $categories = self::find()->where(['country_id' => $countryId, 'status'=>self::STATUS_ACTIVE])->all();
        Yii::$app->redis->set(self::CACHE_KEY_CATEGORY.'.'.$countryId, serialize($categories));

        return $categories;
    }


    public static function getsubByCat($countryId){
        return (new Query())
            ->select('*')
            ->from(self::tableName())
            ->innerJoin(SubCategories::tableName(), 'categories.id = subCategories.category_id')
            ->where([ 'categories.status' => self::STATUS_ACTIVE,
                'subCategories.country_id'=>$countryId,
                'subCategories.status' => 1])
            ->all();

    }
}


