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
//        return $field . "_" . $fieldTypeParams['model'] . "_" . $charType;

    }

// TODO::THIS FUNCTION

    protected function copyingFields()
    {

    }










    //    public array $documentUpdated = [];


//    public function add($fields)
//    {
//        $doc = [];
//        foreach ($fields as $item => $value) {
//
//            $doc[$item] = array("set" => $value);
//        }
//        $this->documentUpdated = $doc;
//
//        return $this;
//    }

//
//    public function set(array $fields)
//    {
//        $doc = [];
//        foreach ($fields as $item => $value) {
//
//            $doc[$item] = array("set" => $value);
//        }
//        $this->documentUpdated = $doc;
////        echo Json::encode($doc);die;
//        return $this;
//    }


//curl -X POST -H 'Content-Type: application/json' 'http://localhost:8983/solr/core_docs/update?commitWithin=1000' --data-binary '[
// {"id"         : "book1",
//  "author_s"   : {"set":"Neal Stephenson"},
//  "copies_i"   : {"inc":3},
//  "cat_ss"     : {"add":"Cyberpunk"}
// }
//]'


}