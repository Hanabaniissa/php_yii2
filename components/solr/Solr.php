<?php

namespace app\components\solr;

use yii\base\Component;
use yii\helpers\Json;

class Solr extends Component
{

    public string $protocol;

    public string $port;

    public string $host;

    public string $path;


    public function getUrl($core, $process): string
    {

        $protocol = $this->protocol;
        $host = $this->host;
        $port = $this->port;
        $path = $this->path;

        return $protocol . "://" . $host . ":" . $port . $path . $core . "/" . $process;
    }

    public function configWithCurl($configParams)
    {

        $url = self::getUrl($configParams['core'], $configParams['process']);

        $data_json = Json::encode($configParams['data']);

        $ch = curl_init();
        $header = array('Content-Type: application/json');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch ($configParams['method']) {

            case 'post':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                break;

            case 'get':
                $method = '';
                break;

            default:
                return die('null');
        }

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

        $data = curl_exec($ch);
        $return = 0;
        if (!curl_errno($ch)) {
            $return = $data;
        }
        curl_close($ch);
        return $return;
    }


}