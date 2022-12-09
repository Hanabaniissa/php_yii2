<?php

namespace app\components\solr;

class Facet extends Query
{
    public string $query;
    public string $field;
    public string $prefix;
    public string $contain;
    public string $IgnoreCase;
    public int $limit;
    public string $matches;
    public string $sort;
    public string $minCount;
    public string $missing;


}