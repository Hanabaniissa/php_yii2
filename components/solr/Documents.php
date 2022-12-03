<?php

namespace app\components\solr;


use app\models\Solr;
use app\modules\api\models\Post;

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

        $data[] = self::getFields($dataConfigParams['model'], $dataConfigParams['id'], $dataConfigParams['modelName']);

        $docConfigParams = [
            'method' => 'post',
            'core' => $dataConfigParams['core'],
            'process' => 'update/json/docs?commit=true',
            'data' => $data,
        ];
        return \Yii::$app->solr->configWithCurl($docConfigParams);

    }


    protected static function getFields($model, $id_model, $modelName): array
    {
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


}