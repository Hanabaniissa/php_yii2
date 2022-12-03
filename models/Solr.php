<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

class Solr extends Model
{

    public static function getPostKeys($model, $id_model,$modelName)
    {
        if (post::class == $model) {
            $model_object = post::find()->where(['id' => $id_model])->one();
//            $posts=\app\modules\api\models\Post::find()->where(['id'=>'108'])->one();
//            echo \Psy\Util\Json::encode($model_object);die;

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
            var_dump($values);die;

            foreach ($values as $value) {

                $field = $value->field;
                $option = $value->option;

                $fieldTypeParamsValue = [
                    'value' => $option->label_en,
                    'key' => $field->label_en,
                    'search' => ' ',
                    'replace' => '_',
                    'model'=>'Field',
                ];

                $field = self::getFieldType($fieldTypeParamsValue);

                $attributes[$field] = $option->label_en;

//                $type = $field->type;
//                $field = str_replace(' ', '_', strtolower($field->label_en));
//                $field .= "_field_" . self::getFieldTypeForSolr($type);
//                $attributes[$field] = $option->label_en;
            }


            return $attributes;
        } else {

            $model_object = $model::find()->where(['id' => $id_model])->one();
            foreach ($model_object as $field_key => $value) {
                $key = $field_key;
                $key_value = $value;
                $type = gettype($value);
                $field = str_replace('', '_', strtolower($key));
                $modelName = str_replace('app\models\\', '_', strtolower($model));
                $field .= $modelName . "_" . self::getFieldTypeForSolr($type);
                $attributes[$field] = $key_value;
            }

            return $attributes;
        }
    }

    private function getWithRelation($model, $id_model, $attributes)
    {
        return $attributes;

    }

    private function getWithoutRelation($model, $id_model, $attributes)
    {
        return $attributes;


    }

// or create relation for country with post

    private static function getFieldType($fieldTypeParams): string
    {

        $type = gettype($fieldTypeParams['value']);
        $field = str_replace($fieldTypeParams['search'], $fieldTypeParams['replace'], strtolower($fieldTypeParams['key']));

//        if (!$fieldTypeParams['model'] == null) {
//            $modelName = str_replace('app\models\\', '_', strtolower($fieldTypeParams['model']));
//        } else {
//            $modelName='_';
//        }

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
                return die('null');
        }

        $field .= "_".$fieldTypeParams['model'] . "_".$charType;

        return $field;

    }

private static function getFieldTypeForSolr($field): string
{
    switch ($field) {
        case 'integer':
            return 'i';
            break;
        case 'string':
            return 's';
            break;
        case 'Int':
            return 'i';
            break;
        case 'String':
            return 's';
        default:
            return 'null';
    }
}


}