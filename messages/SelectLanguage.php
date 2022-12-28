<?php

namespace app\messages;

use yii\base\BootstrapInterface;

class SelectLanguage implements BootstrapInterface
{
    public $supported_languages = [];

    public function bootstrap($app)
    {
        $supported_languages = isset($app->request->cookies['language']) ? (string)$app->request->cookies['language'] : null;
        if (empty($supported_languages)) {
            $supported_languages = $app->request->getPreferredLanguage($this->supported_languages);
        }
        \Yii::$app->language = $supported_languages;
    }


}