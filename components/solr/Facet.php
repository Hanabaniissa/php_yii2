<?php

namespace app\components\solr;

use Yii;

class Facet extends Solr
{
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

    public function sort(array $fields): Facet
    {
        $sort = '';
        $count = 1;
        foreach ($fields as $field => $sortOperation) {
            $sort .= $field . " " . $sortOperation;
            if ($count != count($fields)) {
                $sort .= ', ';
                $count++;
            }
        }
        $this->bodyFacets['sort'] =  $sort;
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

}