<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "post_value".
 *
 * @property int $id
 * @property int $post_id
 * @property int $field_id
 * @property int $option_id
 * @property string|null $string_val
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 * @property-read Field $field
 * @property-read Option $option
 */
class PostValue extends ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;


    public static function tableName(): string
    {
        return 'post_value';
    }

    public function rules(): array
    {
        return [
            [['created_by'], 'default', 'value' => Yii::$app->user->id],
            [['post_id', 'field_id', 'option_id', 'created_by'], 'required'],
            [['post_id', 'field_id', 'option_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['string_val'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
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

    public static function getPostvalueWithIdQuery(): Query
    {

        return (new Query())->select('*')
            ->from(self::tableName())
            ->innerJoin(post::tableName(), 'post_value.post_id = posts.id');

    }

    public function getField(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Field::class, ['id' => 'field_id']);
    }

    public function getOption(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Option::class, ['id' => 'option_id']);
    }

    public function delete(){
        $this->status = self::STATUS_DELETED;
        $this->save(false);
    }


}