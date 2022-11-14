<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "options".
 *
 * @property int $id
 * @property string $label_ar
 * @property string $label_en
 * @property int $field_id
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 */
class Option extends ActiveRecord{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;


    public static function tableName()
    {
        return 'options';
    }
    public function rules()
    {
        return [
            [['label_ar', 'label_en', 'title', 'field_id', 'created_by'], 'required'],
            [['field_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['label_ar', 'label_en', 'title'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label_ar' => Yii::t('app', 'Label Ar'),
            'label_en' => Yii::t('app', 'Label En'),
            'title' => Yii::t('app', 'Title'),
            'field_id' => Yii::t('app', 'Field ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    public static function getOptionByFieldQuery($fieldId){
        return self::find()->where(['field_id'=>$fieldId]);


    }







}