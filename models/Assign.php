<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "assign".
 *
 * @property int $id
 * @property int $field_id
 * @property int $subCategory_id
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 */
class Assign extends ActiveRecord{


    public static function tableName()
    {
        return 'assign';
    }

    public function rules()
    {
        return [
            [['field_id', 'subCategory_id', 'created_by'], 'required'],
            [['field_id', 'subCategory_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'field_id' => Yii::t('app', 'Field ID'),
            'subCategory_id' => Yii::t('app', 'Sub Category ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }


}