<?php

namespace app\components\solr;

use Yii;
use ReflectionClass;
use yii\base\Action;
use yii\db\Exception;
use yii\base\InvalidConfigException;

class Document extends Field
{
    public string $documentType = 'json';
    public string $overwrite = 'true';
    public array $documents = [];
    public string $action = '/update/';
    public int $commitWithin = 1000;

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function save($models, $temp_model)
    {
        $this->prepare($models, $temp_model);
        $action = $this->getAction($this->action .= $this->documentType . '/docs');
        return Yii::$app->solr->configWithCurl('post', $action, $this->documents);
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
            $modelName = strtolower($modelName);
            $this->documents[$modelName] = Field::getFieldsWithoutRelationModel($model, $id_model, $modelName);
            if ($modelName === 'post') {
                $this->documents['field'] = Field::getFieldsWithRelationModel($model, $id_model, $modelName);
            }
        }
    }

    public function update()
    {
        $action = $this->getAction($this->action .= $this->documentType);
        $documents[] = $this->documents;
        return Yii::$app->solr->configWithCurl('post', $action, $documents);
    }

    private function getAction($action): string
    {
        return $action . '?commitWithin=' . $this->commitWithin . '&overwrite=' . $this->overwrite;
    }

    public function from(int $docID): Document
    {
        $this->documents['id'] = $docID;
        return $this;
    }

    public function add(array $fields): Document
    {
        $this->prepareUpdateAction($fields, 'get');
        return $this;
    }

    public function set(array $fields): Document
    {
        $this->prepareUpdateAction($fields, 'set');
        return $this;
    }

    private function prepareUpdateAction(array $fields, string $action): void
    {
        foreach ($fields as $item => $value) {
            $this->documents[$item] = [$action => $value];
        }
    }

//TODO :: Create Schema Class
    public function copyingFields(array $source, string $dest)
    {
        $this->addField($dest);
        $action = $this->prepareSchemaAction();
        foreach ($source as $item) {
            $this->documents = [
                "add-copy-field" => [
                    "source" => $item,
                    "dest" => [$dest]
                ]
            ];
        }
        return Yii::$app->solr->configWithCurl('post', $action, $this->documents);
    }

    protected function addField(string $name)
    {
        $action = $this->prepareSchemaAction();
        $this->documents = [
            "add-field" => [
                "name" => $name,
                "type" => "text_en",
                "indexed" => "true",
                "stored" => "false",
                "multiValued" => "true"
            ]];
        return Yii::$app->solr->configWithCurl('post', $action, $this->documents);
    }

    private function prepareSchemaAction(): string
    {
        return '/schema?CommitWithin=' . $this->commitWithin;

    }


//TODO
    public function delete($da)
    {
        $data = ['delete' => $da];
        $action = '/update/' . $this->documentType;
        $action = $this->getAction($action);
        return Yii::$app->solr->configWithCurl('post', $action, $data);
    }

    public function overwrite($trueOrFalse): Document
    {
        $this->overwrite = $trueOrFalse;
        return $this;
    }

}