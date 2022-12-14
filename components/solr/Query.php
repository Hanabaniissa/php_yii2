<?php

namespace app\components\solr;

use Exception;
use Yii;

class Query extends Solr
{
    /** requestHandler (qt) */
    public string $requestHandler = '/select?';

    public string $query = '*:*';

    /** queryOperation(q.op) */
    public string $queryOperation = 'OR';

    public string $indent = 'true';

    /** filterQuery(fq) */
    public string $filterQuery = '';

    public string $debugQuery = 'false';

    /** fieldList(fl) */
    public string $fieldList = '*';

    /** defType() */
    public string $defType = 'lucene';

    /** responseWriter(wt) */
    public string $responseWriter = 'json';

    public string $sort = 'score%20desc';

    public int $rows = 10;

    /** defaultField(df) */
    public string $defaultField = '';

    public int $page = 1;

    private int $start = 0;

    private array $queryUrl = [];
    public string $field = '';
    public string $prefix = '';
    public int $minCount = 0;


    public function get()
    {
        $action = $this->getAction();
        return $this->prepareDocs($action);
    }


    private function prepareDocs($action)
    {
        $data = json_decode(Yii::$app->solr->configWithCurl('get', $action));
        return $data->response->docs;
    }

    public function getAction($queryUrl = ''): string
    {
        $this->setQueryUrl();
        $action = $this->requestHandler;
        foreach ($this->queryUrl as $item => $value) {
            $action .= $item . '=' . $value . '&';
        }
        $action .= $queryUrl;
        return $action;
    }

    public function setQueryUrl(): array
    {
        $this->queryUrl = [
            'q' => $this->query,
            'q.op' => $this->queryOperation,
            'rows' => $this->rows,
            'start' => $this->start,
            'indent' => $this->indent,
            'wt' => $this->responseWriter,
            'debugQuery' => $this->debugQuery,
            'fq' => $this->filterQuery,
            'sort' => $this->sort,
            'fl' => $this->fieldList,
            'defType' => $this->defType,
//            'facet' => 'false'
        ];
        return $this->queryUrl;
    }

    public function page(int $page): Query
    {
        $this->page = $page;
        return $this;
    }

    public function getStart()
    {
        return $this->start = $this->page * $this->rows + 1;
    }

    public function one(): Query
    {
        $this->rows = 1;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function query(array $fields = []): Query
    {
        $q = '';
        foreach ($fields as $field => $value) {
            $q .= $field . "%20:%20" . $value . "%20";
        }
        $this->query = $q;
        return $this;
    }

//    public function defaultField(string $df)
//    {
//
//    }

    public function responseWriter(string $wt): Query
    {
        $this->responseWriter = $wt;
        return $this;
    }

    public function queryOperation(string $q_op): Query
    {
        $this->queryOperation = $q_op;
        return $this;
    }

    public function sort(array $fields): Query
    {
        $sort = '';
        $count = 1;

        foreach ($fields as $field => $sortOperation) {
            $sort .= $field . "%20" . $sortOperation;
            if ($count != count($fields)) {
                $sort .= ',%20';
                $count++;
            }
        }
        $this->sort = $sort;
        return $this;
    }

    public function select(array $fieldList): Query
    {
        if (!$fieldList[0] == ('*' || '*,dv_field_name' || '* score')) {
            $field = '';
            foreach ($fieldList as $item) {
                $field .= $item . '%20';
            }
            $this->fieldList = $field;
            return $this;
        }
        $this->fieldList = str_replace(' ', '%20', $fieldList [0]);
        return $this;
    }

    public function filterQuery(array $fl): Query
    {
        $q = '';
        foreach ($fl as $item => $value) {
            $q .= $item . "%20:%20" . $value . "%20";
        }
        $this->filterQuery = $q;
        return $this;
    }

    public function debugQuery(bool $boolean): Query
    {
        $this->debugQuery = !$boolean == 1 ? 'true' : 'false';
        return $this;
    }

    public function indent(bool $boolean): Query
    {
        $this->debugQuery = !$boolean == 0 ? 'true' : 'false';
        return $this;
    }

    public function defType($defType): Query
    {
        $this->defType = $defType;
        return $this;
    }

}
