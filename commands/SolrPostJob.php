<?php

namespace app\commands;

use app\components\solr\Solr;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\queue\JobInterface;

class SolrPostJob extends BaseObject implements JobInterface
{
    public $models;
    public $temp_model;
    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function execute($queue)
    {
        Solr::find('posts_new')->useDocument()->save($this->models, $this->temp_model);
        echo 'true';
    }
}