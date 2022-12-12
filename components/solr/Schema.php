<?php

namespace app\components\solr;

use Yii;

class Schema extends Solr
{
    public int $commitWithin = 1000;
    public array $data = [];

    public function copyingFields(array $source, string $dest)
    {
        $this->addField($dest);
        $action = '/schema?CommitWithin=' . $this->commitWithin;
        foreach ($source as $item) {
            $this->data = [
                "add-copy-field" => [
                    "source" => $item,
                    "dest" => [$dest]
                ]
            ];
        }
        return Yii::$app->solr->configWithCurl('post', $action, $this->data);
    }

//curl -X POST -H 'Content-Type: application/json' 'http://localhost:8983/solr/core_docs/schema?CommitWithin=1000' --data-binary
// '{"add-copy-field":{"source":"title_t","dest":["catch_all"]}}'

    public function addField(string $name)
    {
        $action = '/schema?CommitWithin=' . $this->commitWithin;
        $this->data = [
            "add-field" => [
                "name" => $name,
                "type" => "text_en",
                "indexed" => "true",
                "stored" => "false",
                "multiValued" => "true"
            ]];
        return Yii::$app->solr->configWithCurl('post', $action, $this->data);
    }


//curl -X POST -H 'Content-Type: application/json' 'http://localhost:8983/solr/core_docs/schema?CommitWithin=1000' --data-binary '{
//            "add-field":{"name":"catch_all","type":"text_en","indexed":true,"stored":false,"multiValued":true}}'


}