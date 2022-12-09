<?php

namespace app\components\solr;

use Yii;
use ReflectionClass;
use yii\db\Exception;
use yii\base\InvalidConfigException;

class Document extends Field
{
    public string $documentType = 'json';
    public string $overwrite = 'true';
    public array $documents = [];
    public int $commitWithin = 1000;

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function save($models, $temp_model)
    {
        $this->prepare($models, $temp_model);

        $subUrl = '/update/' . $this->documentType . '/docs?commitWithin=';
        $subUrl = $this->subUrl($subUrl);

        return Yii::$app->solr->configWithCurl('post', $subUrl, $this->documents);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function prepare($models, $temp_model)
    {
        $this->documents = $temp_model;
        foreach ($models as $model => $id_model) {
            $reflect = new ReflectionClass(Yii::createObject($model));
            $modelName = $reflect->getShortName();

            $this->documents[$modelName] = Field::getFieldsWithoutRelationModel($model, $id_model, $modelName);
            if ($modelName === 'post') {
                $this->documents['field'] = Field::getFieldsWithRelationModel($model, $id_model, $modelName);
            }
        }
    }

    public function createOne($temp_post)
    {
        $this->documents = $temp_post;
        $subUrl = '/update/' . $this->documentType . '/docs?commitWithin=';
        $subUrl = $this->subUrl($subUrl);

        return Yii::$app->solr->configWithCurl('post', $subUrl, $this->documents);
    }

    public function update()
    {
        $subUrl = '/update?commitWithin=';
        $subUrl = $this->subUrl($subUrl);
        $documents[] = $this->documents;
        return Yii::$app->solr->configWithCurl('post', $subUrl, $documents);

    }

    private function subUrl($subUrl): string
    {
        return $subUrl . $this->commitWithin .
            '&overwrite=' . $this->overwrite;
    }

    public function from(int $docID): Document
    {
        $this->documents['id'] = $docID;
        return $this;
    }


    public function add($fields): Document
    {
        foreach ($fields as $item => $value) {
            $this->documents[$item] = array("add" => $value);
        }
        return $this;
    }


    public function set(array $fields): Document
    {
        foreach ($fields as $item => $value) {
            $this->documents[$item] = array("set" => $value);
        }
        return $this;
    }

//TODO

    public function delete($da)
    {
        $data = ['delete' => $da];
        $subUrl = '/update/docs?commitWithin=' . $this->commitWithin;
        return Yii::$app->solr->configWithCurl('post', $subUrl, $data);
    }

    public function overwrite($trueOrFalse): Document
    {
        $this->overwrite = $trueOrFalse;
        return $this;
    }
}