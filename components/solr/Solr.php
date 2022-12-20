<?php

namespace app\components\solr;

use Yii;
use yii\base\Component;
use yii\db\Exception;
use yii\helpers\Json;
use function PHPUnit\Framework\classHasAttribute;

/**
 * @property Query $query
 * @property Document $document
 *
 */
class Solr extends Component
{
    public string $protocol;
    public string $core;
    public string $port;
    public string $host;
    public string $path;


    public function getUrl($action): string
    {
        return $this->protocol . "://" . $this->host . ":" . $this->port .
            $this->path . $this->core . $action;
    }

    public function configWithCurl($method, $action, $body = [])
    {
//        $action = "/select?q=id_post_i%20:%20176%20&q.op=OR&rows=10&start=0&indent=true&wt=json&debugQuery=false&fq=&sort=score%20desc&fl=*&defType=lucene&";
//        $action =rawurlencode($action);
//        dd($action);

        $url = self::getUrl($action);
//        var_dump(Json::encode($body));die;
        $ch = curl_init();
        $header = ['Content-Type: application/json'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        switch ($method) {
            case 'post':
                curl_setopt($ch, CURLOPT_POST, 1);
                $data_json = Json::encode($body);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                break;
            case 'get':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                break;
            case 'getFacet':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                $data_json = Json::encode($body);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                break;

            default:
                return die('null');
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
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
    /**
     * @throws Exception
     */
    public static function find($core): Solr
    {
        if (empty($core)) {
            throw new Exception("Core shouldn't be empty.");
        }
        Yii::$app->solr->core = $core;
        return new self();
    }

    public function load(array $data) {
        foreach ($data as $attribute => $value) {
            if (!classHasAttribute(self::class, $attribute)) continue;
            $this->{$attribute} = $value;
        }
        return $this;
    }

    public function useQuery(): Query
    {
        return new Query();
    }

    public function useDocument(): Document
    {
        return new Document();
    }


    public function useSchema(): Schema
    {
        return new Schema();
    }

    public function useFacet(): Facet
    {
        return new Facet();
    }

    /**
     * @throws Exception
     */


//    public static function core($core): Solr
//    {
//        if (!$core) {
//            throw new Exception("Null");
//        }
//
//        Yii::$app->solr->core = $core;
//        return new self;
//    }

}