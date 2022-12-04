<?php

namespace app\components\solr;

use Yii;
use yii\base\Component;
use yii\db\Exception;
use yii\helpers\Json;
/**
 * @property Query $query
 */

class Solr extends Component
{

    public string $protocol;

    public string $port;

    public string $host;

    public string $path;

    public string $core;


//    public function getUrl($core, $process): string
//    {
//
//        $protocol = $this->protocol;
//        $host = $this->host;
//        $port = $this->port;
//        $path = $this->path;
//
//        return $protocol . "://" . $host . ":" . $port . $path . $core . "/" . $process;
//    }


    public function getUrl ($process): string
    {

        $protocol = $this->protocol;
        $host = $this->host;
        $port = $this->port;
        $path = $this->path;
        $core= $this->core;

        return $protocol . "://" . $host . ":" . $port . $path . $core . "/" . $process;
    }

    public function configWithCurl($configParams)
    {
        $url = self::getUrl($configParams['process']);
        $ch = curl_init();
        $header = array('Content-Type: application/json');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch ($configParams['method']) {

            case 'post':
                curl_setopt($ch, CURLOPT_POST, 1);
                $data_json = Json::encode($configParams['data']);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                break;

            case 'get':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

                break;

            default:
                return die('null');
        }

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
//        var_dump($ch);die;

        $data = curl_exec($ch);
        $return = 0;
        if (!curl_errno($ch)) {

            $return = $data;
        }
        curl_close($ch);
        return $return;
    }


//    public static function setCore($core){
//        if($core == null){
//            return die("null");
//        }
//        else{
//            \Yii::$app->solr->core=$core;
//        }
//    }

    /**
     * @throws Exception
     */
    public static function core($core): Query
    {
        if(!$core){
            throw new Exception("Null");
        }

        Yii::$app->solr->core=$core;
        return new Query();
    }

//curl http://localhost:8983/solr/test_dynamic/schema?wt=json
}