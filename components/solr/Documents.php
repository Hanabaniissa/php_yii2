<?php

namespace app\components\solr;


use app\models\Solr;
use app\modules\api\models\Post;
use yii\helpers\Json;

class Documents extends Solr
{

//   $dataConfigParams=[
//           'id'=>$id,
//           'model'=>self::class,
//           'core'=>$core,
//           'modelName'=>'',
//       ];


    public static function create($dataConfigParams)
    {

        $data[] = self::getFields($dataConfigParams['id'], $dataConfigParams['model'], $dataConfigParams['modelName']);

        $docConfigParam = [
            'method' => 'post',
            'core' => $dataConfigParams['core'],
            'process' => 'update/json/docs?commit=true',
            'data' =>
                Json::encode($data),
        ];

        return \Yii::$app->solr->configWithCurl($docConfigParam);

//        $dataCreate=\Yii::$app->solr->configWithCurl($docConfigParam);
//        return 'true';
    }

    /**
     **@property Post $model
     */

//    protected static function getFields($id, $model)
//    {
//
////        $data_id = $id;
//        $attributes['id'] = $id;
//        $data = $model::find()->where(['id' => $id])->one();
//
//        foreach ($data as $postKeys => $value) {
//            $key = $postKeys;
//            $key_value = $value;
//            $type = gettype($value);
//            $field = str_replace('', '_', strtolower($key));
//            $field .= "_post_" . self::getFieldType($type);
//            $attributes[$field] = $key_value;
//        }
//
//        return $attributes;
//
//    }


    protected static function getFields($model, $id_model, $modelName): array
    {

//        $data_id = $id;
        $attributes['id'] = $id_model;
        $data = $model::find()->where(['id' => $id_model])->one();

        foreach ($data as $field_key => $value) {

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
                return die('null');
        }

        $field .= "_" . $fieldTypeParams['model'] . "_" . $charType;

        return $field;

    }


    public function update()
    {


    }

    public function delete()
    {


    }








//    private static function getFieldType($field): string
//    {
//        switch ($field) {
//            case 'Int':
//            case 'integer':
//                return 'i';
//
//            case 'String':
//            case 'string':
//                return 's';
//
//            default:
//                return 'null';
//        }
//    }

}