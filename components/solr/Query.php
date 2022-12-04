<?php

namespace app\components\solr;


use Exception;

class Query extends Solr
{
    public string $qt = '/select?';

    public string $q = '*:*';

    public string $q_op = 'OR';

    public bool $indent = true;

    public string $fq ='';

    public string $sort = 'score desc';

    public int $start = 0;

    public int $rows = 10;

    public string $debugQuery = "false";

    //field list
    public string $fl = '*';

    public string $df='';

    public string $wt = 'json';

    public string $defType = 'lucene';

    public int $limit = 30;


    public function get()
    {

//        $qt = '/select?';

        $process = $this->qt . "q=" . $this->q . "&q.op=" . $this->q_op . "&wt=" . $this->wt
            . "&indent=" . $this->indent . "&rows=" . $this->rows . "&start=" . $this->start
            . "&sort=" . $this->sort . "&fl=" . $this->fl . "&defType=" . $this->defType . "&fq=" . $this->fq . "&df=" . $this->df ;

        $docConfigParams = [
            'method' => 'get',
            'core' => 'test_dynamic',
            'process' => $process,
        ];
        $data=[];

//        for ($i = 0; $i >= $this->limit; $i++) {
//
//         $data=  \Yii::$app->solr->configWithCurl($docConfigParams);
//        }
//
//        return $data;

//        $process = $this->qt . "q=" . $this->q;


        return \Yii::$app->solr->configWithCurl($docConfigParams);
    }

    public function rows($start, $rows)
    {
        $this->start = $start;
        $this->rows = $rows;
        return $this->$this;
    }

    public function limit(int $limit): int
    {
        return $this->limit = $limit;
    }

    public function one()
    {
        \Yii::$app->solr->query->rows = 1;
    }

    /**
     * @throws Exception
     */

    public function from($core)
    {
        if (!$core) {
            throw new Exception("Null");
        }

        \Yii::$app->solr->core = $core;


    }

    public function query($q)
    {
        return $this->q = $q;

    }

//    public function query($field='',$value='',$condition=''){
//        return $this->q=$field.":".$value.$condition;
//    }

    public function ResponseWriter($wt)
    {
        return $this->wt = $wt;
    }


//    public function fieldList($field = [])
//    {
//        return $this->fl = $field;
//    }

    public function queryOperation($q_op)
    {
        return $this->q_op = $q_op;
    }

    public function sort($field, $sort)
    {
        return $this->sort = $field . " " . $sort;
    }

    public function select($fl): string
    {
        if (!$this->fl == '*') {
            return $this->fl = $fl;
        }
        $this->qt = '/select?';
        return $this->$this;
    }

    public function filterQuery($field, $value = '')
    {
        if (!$this->fl == '*') {
            return $this->fq = $field . ":" . $value;
        }
        return $this->$this;
    }


    public function debugQuery($debugQuery)
    {
        return $this->debugQuery = $debugQuery;
    }


    public function indent($indent)
    {
        return $this->indent = $indent;
    }


    public function search()
    {
//        $this->qt = '/query?';
////        $fl = \Yii::$app->solr->query->fl;
//        return $this->$this;
    }

    //    public static function where()
//    {
//
//    }

//    public function all()
//    {
//        //count doc
////        $doc =
//        \Yii::$app->solr->query->rows = count('');
//
//    }


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

//curl http://localhost:8983/solr/test_dynamic/  schema?wt=json
//     http://www.somesite.com/solr/collection1/  select?q=Motorbike&wt=json&indent=true&defType=edismax&stopwords=true&lowercaseOperators=true
//curl http://localhost:8983/solr/test_dynamic/query?q=*:*&q.op=OR&indent=true&rows=1000


}