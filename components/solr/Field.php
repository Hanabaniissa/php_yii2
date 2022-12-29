<?php

namespace app\components\solr;

use app\models\post;
use yii\db\Exception;

class Field extends Solr
{

    /**
     * @throws Exception
     */
    public static function getFieldsWithoutRelationModel($model, $id_model, $modelName): array
    {
        $attributes = [];

        $model_object = self::getObjectFromModel($model, $id_model);

        foreach ($model_object as $field_key => $value) {

            $fieldTypeParams = [
                'value' => $value,
                'key' => $field_key,
                'model' => $modelName,
            ];

            $field = self::getDynamicFieldType($fieldTypeParams);

            $attributes[$field] = $value;
        }

        return $attributes;
    }

    /**
     * @throws Exception
     */
    public static function getFieldsWithRelationModel($model, $id_model, $relationField): array
    {
        $attributes = [];
        $post = post::find()->where(['id' => $id_model])->one();
        $values = $post->value;
        foreach ($values as $value) {

            $field = $value->field;

            $option = $value->option;

            $fieldTypeParamsValue = [
                'value' => $option->label_en,
                'key' => $field->label_en,
                'search' => ' ',
                'replace' => '_',
                'model' => $relationField,
            ];


            $field = self::getDynamicFieldType($fieldTypeParamsValue);
            $attributes[$field] = $option->label_en;

        }

        return $attributes;

    }

    /**
     * @throws Exception
     */


    private static function getObjectFromModel($model, $id)
    {
        return $model::find()->where(['id' => $id])->one();

    }

    public static function getValueTypeForSolr($value): string
    {
        $type = gettype($value);
        if (is_numeric($value)) $type = 'integer';
        switch ($type) {
            case 'Int':
            case 'integer':
                return 'i';
            case 'string':
            case 'String':
                return 's';
            default:
                return 'str';
        }
    }

    private static function getDynamicFieldType($fieldTypeParams): string
    {
        $type = gettype($fieldTypeParams['value']);
        $field = strtolower($fieldTypeParams['key']);

        switch ($type) {
            case 'Int':
            case 'integer':
                $charType = 'i';

                break;
            case 'string':
            case 'String':
                $charType = 's';
                break;

            default:
                return 'str';
        }
        return $field . "_" . $charType;
    }

}