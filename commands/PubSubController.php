<?php

namespace app\commands;

use app\components\solr\Solr;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\db\Exception;

class PubSubController extends Controller
{
    const CHANNEL_REDIS = 'posts_solr';

    public function actionSubscribe()
    {
        $redisClient = new \Redis();
        $redisClient->connect('localhost', 6379);
        $redisClient->psubscribe([self::CHANNEL_REDIS], [$this, 'savePostToSolr']);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function savePostToSolr($redis, $pattern, $chan, $msg)
    {
        $message = unserialize($msg);
        $models = $message['models'];
        $temp_model = $message ['temp_model'];
        Solr::find('posts_new')->useDocument()->save($models, $temp_model);
        echo 'true';

    }
}