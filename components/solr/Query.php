<?php

namespace app\components\solr;


class Query extends Solr
{
    public string $qt = '/select';

    public string $q = '*:*';

    public string $q_op = 'OR';

    public bool $indent = true;

    public string $fq;

    public string $sort;

    public int $start = 0;

    public int $rows = 10;

    public string $debugQuery = "false";

    //field list
    public string $fl;

    public string $df;

    public string $wt;

    public string $defType;

    public int $limit = 30;


    public function get()
    {
        $qt = '/query?';
        $q = \Yii::$app->solr->query->q;


//
//        $param = [
//            'qt' => '/select?',
//
//        ];
//        $queryParams = [
//            'q' => 'h',
//            'q_op' => 'AND',
//            'fq' => '',
//            'wt' => 'json',
//            'indent' =>'',
//        ];

        $process = $this->qt;
        $docConfigParams = [
            'method' => 'post',
            'core' => 'test_dynamic',
            'process' => $process,
        ];

        return \Yii::$app->solr->configWithCurl($docConfigParams);
    }

    public function row($rows)
    {
        \Yii::$app->solr->query->rows = $rows;
    }

    public static function from($core)
    {
        if ($core == null) return die("null");
        else {
            \Yii::$app->solr->core = $core;
        }
    }

    public static function search()
    {
        $qt = '/query?';
        $q = \Yii::$app->solr->query->q;

    }

    public function select()
    {
//        \Yii::$app->solr->query->qt = '/select?';
        return $this->qt = '/select?';
    }

//    public static function find()
//    {
//
//    }

    public static function where()
    {

    }

    public function limit(int $limit) {
        return $this->limit = $limit;
    }

    public function one()
    {
        \Yii::$app->solr->query->rows = 1;
    }


    public function all()
    {
        //count doc
//        $doc =
        \Yii::$app->solr->query->rows = count('');

    }



//curl http://localhost:8983/solr/test_dynamic/  schema?wt=json
//     http://www.somesite.com/solr/collection1/  select?q=Motorbike&wt=json&indent=true&defType=edismax&stopwords=true&lowercaseOperators=true
//curl http://localhost:8983/solr/test_dynamic/query?q=*:*&q.op=OR&indent=true&rows=1000



}