<?php

namespace app\components\solr;

use Yii;

class Facet extends Solr
{
    //TODO
    public array $bodyFacets = [];

    public function setQuery(array $query, string $operation): Facet
    {
        if (!empty($query)) {
            $this->bodyFacets['query'] = $this->prepareQuery($query, $operation);
        } else $this->bodyFacets['query'] = '*:*';
        return $this;
    }

    private function prepareQuery(array $query, string $operation): string
    {
        $facetQuery = '';
        $count = 1;
        foreach ($query as $field => $value) {
            $facetQuery .= $field . ':' . $value . ' ';
            if ($count != count($query)) {
                $facetQuery .= $operation . ' ';
                $count++;
            }
        }
        return $facetQuery;
    }

    public function withFilter(array $query, string $operation): Facet
    {
        $this->bodyFacets['filter'] = $this->prepareQuery($query, $operation);
        return $this;
    }

    public function termFacet(string $name, string $field, int $limit): Facet
    {
        $param = $this->prepareFacetParams($name, $field, $limit);
        $this->bodyFacets['facet'] = $param;
        return $this;
    }

    public function nestedTermFacet(string $name, string $field, int $limit): Facet
    {
//        foreach ($this->bodyFacets as $facet) {
//            }
        $this->bodyFacets['facet']['categories']['facet'] = $this->prepareFacetParams($name, $field, $limit);
        return $this;
    }

    private function prepareFacetParams(string $name, string $field, int $limit): array
    {
        $limit = !empty($limit) ? $limit : 100;
        $facetParams[$name] = [
            'type' => 'terms',
            'field' => $field,
            'limit' => $limit
        ];
        return $facetParams;
    }

    public function getFacet()
    {
        return json_decode(Yii::$app->solr->configWithCurl('getFacet', '/query', $this->bodyFacets));
    }












//    public function setSort(string $sort): Facet
//    {
//        return $this;
//    }
//

//
//    public function setLimit(int $limit): Facet
//    {
//        $this->nameFacet['limit'] = $limit;
//        return $this;
//    }
//
//    public function prepareData($data)
//    {
//        $docs = $data->response->docs;
//        $fieldsFacet= $data->
//        return $data;
//    }


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


//
//'{
//  "query": "*:*",
//  "facet": {
//    "categories": {
//      "type": "terms",
//      "field": "post.subcategory_id_i",
//      "facet": {
//        "top_manufacturer": {
//          "type": "terms",
//          "field": "subcategories.label_en_s",
//        }
//      }
//    }
//  }
//}'


//"query": "post.title_s: *ion*",
//"facet": {
//"categories": {
//"type": "terms",
//"field": "category.label_en_s",
//"facet": {
//"top_manufacturer": {
//"type": "terms",
//"field": "category.id_i"
//}
//}
//}
//}
//}'


}