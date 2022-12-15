<?php

namespace app\models;

use app\components\solr\Solr;
use yii\base\InvalidConfigException;
use yii\db\Exception;

class redis
{

    public static function publish($channel,$message){
        \Yii::$app->redis->publish($channel,$message);
    }


    //await
    public static function subscribe($channel){
        \Yii::$app->redis->subscribe($channel,function($instance, $channel, $message) {

            dd('billing_data');
    });}


    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function savePostToSolr($models,$temp_model){
        Solr::find('posts_new')->useDocument()->save($models, $temp_model);

    }



}