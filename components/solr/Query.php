<?php

namespace app\components\solr;

use app\components\solr\Solr;


class Query extends Solr
{
    public string $qt;

    public string $q;

    public string $q_op;

    public string $fq;

    public string $sort;

    public string $start;

    public string $rows;

    //field list
    public string $fl;

    public string $df;

    public string $wt;

    public string $defType;


    public function get()
    {
        $query = [
            'q' => 'h',
            'q_op' => 'AND',
            'fq' => 'h',
        ];
    }


}