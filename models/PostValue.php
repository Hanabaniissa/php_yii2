<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "post_value".
 *
 * @property int $id
 * @property int $post_id
 * @property int $field_id
 * @property int $option_id
 * @property int|null $integer_val
 * @property string|null $string_val
 * @property int|null $boolean_val
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 */

class PostValue extends ActiveRecord{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;


    public static function tableName()
    {
        return 'post_value';
    }
    public function rules()
    {
        return [
            [['post_id', 'field_id', 'option_id', 'created_by'], 'required'],
            [['post_id', 'field_id', 'option_id', 'integer_val', 'boolean_val', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['string_val'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'field_id' => Yii::t('app', 'Field ID'),
            'option_id' => Yii::t('app', 'Option ID'),
            'integer_val' => Yii::t('app', 'Integer Val'),
            'string_val' => Yii::t('app', 'String Val'),
            'boolean_val' => Yii::t('app', 'Boolean Val'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}