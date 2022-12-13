<?php

namespace app\components\solr;

use Yii;

class Facet extends Query
{
    public string $facet = 'false';
    public string $queryFacet = '';
    public string $field = '';
    public string $prefix = '';
    public int $limit = 100;
    public string $sortFacet = 'count';
    public int $minCount = 0;

    public function facet(bool $boolean)
    {
        $this->facet = !$boolean == 0 ? 'true' : 'false';
        $action = [
            'facet' => $this->facet,
            'facet.field' => $this->field,
            'facet.sort' => $this->sortFacet,
            'facet.mincount' => $this->minCount,
        ];
        return $this->getFacet($action);
    }

    public function getFacet($queryUrl)
    {
        $action = '';
        foreach ($queryUrl as $item => $value) {
            $action .= $item . '=' . $value . '&';
        }
        $action = $this->getAction($action);
        return $this->prepareFacetFields($action);
    }

    public function prepareFacetFields($action)
    {
        $data = json_decode(Yii::$app->solr->configWithCurl('get', $action));
        return $data->facet_counts->facet_fields;
    }

    public function queryFacet(string $query): Facet
    {
        $this->queryFacet = $query;
        return $this;
    }

    public function field(string $field): Facet
    {
        $this->field = $field;
        return $this;
    }

    public function prefix(string $terms): Facet
    {
        $this->prefix = $terms;
        return $this;
    }

    public function limit(int $limit): Facet
    {
        $this->limit = $limit;
        return $this;
    }

    public function sortFacet(string $sortFacet): Facet
    {
        $this->sortFacet = $sortFacet;
        return $this;
    }

    public function minCount(int $minCount): Facet
    {
        $this->minCount = $minCount;
        return $this;
    }



    

//
//curl http://localhost:8983/solr/posts_new/query -d '
//{
//"query": "*:*",
//"facet": {
//"prices": {
//"type": "terms",
//"field": "post.price_i","limit":3
//}
//}
//}'

}