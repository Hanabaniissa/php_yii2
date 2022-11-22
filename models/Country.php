<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

use Yii;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string $label_ar
 * @property string $label_en
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 */
class Country extends ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;


    public static function tableName()
    {
        return 'country';
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
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'updatedByAttribute' => null,

            ],
        ];
    }


//    public function behaviors()
//    {
//        return [
//            [
//                'class' => \yii\behaviors\BlameableBehavior::className(),
//                'value' => Yii::$app->user->id,
//            ],
//
//            [
//                'class' => 'sammaye\audittrail\LoggableBehavior',
//                'userAttribute' => 'updated_by', //blameable attribute of the current model.
//                'ignored' => ['updated_by', 'updated_at'], // This ignores fields from a selection of all fields, not needed with allowed
//            ],
//
//        ];
//    }


    public function rules()
    {
        return [
            [['label_ar', 'label_en'], 'required'],
            [['status', 'created_by', 'updated_by'], 'integer'],
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
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    const CACHE_KEY_COUNTRY = 'country';

    public static function getCountry($useCache = true): array
    {
        if ($useCache) {
            $countries = Yii::$app->redis->get(self::CACHE_KEY_COUNTRY);
            if ($countries) return unserialize($countries);

        }
        $countries = self::find()->where(['status' => self::STATUS_ACTIVE])->all();
        Yii::$app->redis->set(self::CACHE_KEY_COUNTRY, serialize($countries));
        return $countries;
    }


    public static function findCountryId()
    {
        return self::$this->id;

    }
}