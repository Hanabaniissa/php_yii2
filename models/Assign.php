<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

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
class Assign extends ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;

    public static function tableName(): string
    {
        return 'assign';
    }

    public function rules(): array
    {
        return [
            [['field_id', 'subCategory_id', 'created_by'], 'required'],
            [['field_id', 'subCategory_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }


    public function attributeLabels(): array
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

    const CACHE_KEY_ASSIGN = 'assigns';

    public static function getAssignWithFieldQuery($subCatId): Query
    {
        return (new Query())->select('*')
            ->from(self::tableName())
            ->innerJoin(Field::tableName(), 'assign.field_id = fields.id')
            ->where(['assign.status' => self::STATUS_ACTIVE, 'fields.status' => 1, 'assign.subCategory_id' => $subCatId]);
    }


}