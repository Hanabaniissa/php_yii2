<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/** @property integer $id
 * @property string $label_ar
 * @property string $label_en
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int $updated_by
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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['created_by', 'updated_by'], 'integer'],
        ];
    }


    const CACHE_KEY = 'categories';

    static function getCategories($useCache = true): array
    {
        if ($useCache) {
            $categories = Yii::$app->redis->get(self::CACHE_KEY);
            if ($categories) return unserialize($categories);

        }
        $categories = self::find()->all();
        Yii::$app->redis->set(self::CACHE_KEY, serialize($categories));
        return $categories;
    }


}
