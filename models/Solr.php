<?php

namespace app\models;

use yii\base\Model;

class Solr extends Model
{

    public static function getPostKeys($model, $id_model, $modelName): array
    {
        if (post::class == $model) {

            $model_object = post::find()->where(['id' => $id_model])->one();

            foreach ($model_object as $field_key => $value) {

                $fieldTypeParams = [
                    'value' => $value,
                    'key' => $field_key,
                    'search' => ' ',
                    'replace' => '_',
                    'model' => $modelName,
                ];

                $field = self::getFieldType($fieldTypeParams);
                $attributes[$field] = $value;
            }

            $values = $model_object->value;

            foreach ($values as $value) {

                $field = $value->field;
                $option = $value->option;

                $fieldTypeParamsValue = [
                    'value' => $option->label_en,
                    'key' => $field->label_en,
                    'search' => ' ',
                    'replace' => '_',
                    'model' => 'Field',
                ];

                $field = self::getFieldType($fieldTypeParamsValue);

                $attributes[$field] = $option->label_en;
            }

        } else {

            $model_object = $model::find()->where(['id' => $id_model])->one();
            foreach ($model_object as $field_key => $value) {
                $key = $field_key;
                $key_value = $value;
                $type = gettype($value);
                $field = str_replace('', '_', strtolower($key));
                $modelName = str_replace('app\models\\', '_', strtolower($model));
                $field .= $modelName . "_" . self::getFieldType($type);
                $attributes[$field] = $key_value;
            }

        }
        return $attributes;
    }

    public function getWithoutRelation($model, $id_model, $modelName): array
    {
        $model_object = self::getObjectFromModel($model, $id_model);

        foreach ($model_object as $field_key => $value) {

//            $attributes=[];
            $fieldTypeParams = [
                'value' => $value,
                'key' => $field_key,
                'search' => ' ',
                'replace' => '_',
                'model' => $modelName,
            ];

            $field = self::getFieldType($fieldTypeParams);
            $attributes[$field] = $value;
        }

        return $attributes;

    }

    public function getWithRelation($model, $id_model, $relationField): array
    {
        $model_object = self::getObjectFromModel($model, $id_model);

        $values = $model_object->value;

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

            $field = self::getFieldType($fieldTypeParamsValue);

            $attributes[$field] = $option->label_en;
        }

        return $attributes;

    }

    public static function getObjectFromModel($model, $id)
    {
        if ($model == null or $id == null) {
            return die('null');
        }
        return $model::find()->where(['id' => $id])->one();

    }

    private static function getFieldType($fieldTypeParams): string
    {

        $type = gettype($fieldTypeParams['value']);
        $field = str_replace($fieldTypeParams['search'], $fieldTypeParams['replace'], strtolower($fieldTypeParams['key']));

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

        $field .= "_" . $fieldTypeParams['model'] . "_" . $charType;

        return $field;

    }



}