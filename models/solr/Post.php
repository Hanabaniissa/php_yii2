<?php

namespace app\models\solr;

use app\components\solr\Field;
use app\components\solr\Solr;
use Symfony\Component\DomCrawler\Field\InputFormField;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use function PHPUnit\Framework\throwException;

class Post extends Model
{
    const SCENARIO_SEARCH = 'search';
    public $id;
    public $title;
    public $description;
    public $phone;
    public $user_id;
    public $price;
    public $post_image;
    public $category_id;
    public $subcategory_id;
    public $city_id;
    public $neighborhood_id;
    public $country_id;
    public $status;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;


    public function scenarios(): array
    {
        return [
            self::SCENARIO_SEARCH => ['title', 'category_id', 'subcategory_id'],
            ...parent::scenarios()
        ];
    }

    public function rules(): array
    {
        return [
            [['id', 'created_at', 'updated_at', 'user_id', 'created_by'], 'safe'],
            [['title'], 'filter', 'filter' => function ($value) {
                return "*$value*";
            }, 'on' => self::SCENARIO_SEARCH],
            [['title', 'description', 'phone', 'category_id', 'country_id', 'subcategory_id', 'city_id', 'neighborhood_id', 'price'], 'safe'],
            [['status', 'created_by', 'updated_by', 'phone'], 'safe'],
//            [['title', 'description'], 'string', 'max' => 300],
            ['post_image', 'string', 'max' => 255],
            [['post_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'phone' => Yii::t('app', 'Phone'),
            'user_id' => Yii::t('app', 'User'),
            'category_id' => Yii::t('app', 'Category '),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'post_image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'), 'country_id' => Yii::t('app', 'Country'),
            'city_id' => Yii::t('app', 'City'),
            'subCategory_id' => Yii::t('app', 'Sub Category'),
            'neighborhood_id' => Yii::t('app', 'Neighborhood'),
            'price' => Yii::t('app', 'Price')
        ];
    }

    public static function prepareArray($post): array
    {
        $attributes = [];
        unset($post['id']);
        foreach ($post as $item => $value) {
            $key = explode(".", $item)[0];
            $item = preg_replace('/^([A-z0-9]*)\./', '', $item);
            $item = preg_replace('/_([^_]*)$/', '', $item);
            $attributes[$key][$item] = $value;
        }
        return $attributes;
    }

    public static function getPost($postArray): Post
    {
        $post = (array)$postArray;
        $postAttributes = self::prepareArray($post);
        $tempPost = new self();
        $tempPost->load($postAttributes['post'], '');
        return $tempPost;
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function getFacetFields()
    {
        $facetFields = Solr::find('posts_new')
            ->useFacet()
            ->setQuery(['category.id_i' => $this->category_id], 'AND')
            ->withFilter(['post.status_i' => 10], 'AND')
            ->termFacet('categories', 'subcategories.label_en_s', 0)
            ->nestedTermFacet('categoryId', 'subcategories.id_i', 0)
            ->getFacet();
        return self::prepareFacetFields($facetFields);
    }

    public static function prepareFacetFields($data)
    {
        if (!empty($data->facets->count)) {
            $field = [];
            $dataFacet = $data->facets->categories->buckets;
            foreach ($dataFacet as $item) {
                $id = $item->categoryId->buckets[0]->val;
                $field[$id] = $item->val;
            }
            return $field;
        } else return 0;
    }

    /**
     * @throws Exception
     * @throws ForbiddenHttpException
     */
//    public function withFacet()
//    {
//        $solrQuery = $this->convertModelToSolrQuery();
//        $key = ['category.label_en_s', 'subcategories.label_en_s'];
//        $fields = ['category.id_i', 'subcategories.id_i'];
//        switch (count($solrQuery)) {
//            case 1 :
//                return Solr::find('posts_new')
//                    ->useFacet()
//                    ->setQuery($solrQuery, 'AND')
//                    ->withFilter(['post.status_i' => 10], 'AND',)
//                    ->termFacet('categories', 'category.label_en_s', 0)
//                    ->nestedTermFacet('categoryId', 'post.category_id_i', 0)
//                    ->getFacet();
//            case 2 :
//                return Solr::find('posts_new')
//                    ->useFacet()
//                    ->setQuery($solrQuery, 'AND')
//                    ->withFilter(['post.status_i' => 10], 'AND')
//                    ->termFacet('categories', 'subcategories.label_en_s', 0)
//                    ->nestedTermFacet('categoryId', 'post.subcategory_id_i', 0)
//                    ->getFacet();
//            case 3 :
//                return Solr::find('posts_new')
//                    ->useFacet()
//                    ->setQuery($solrQuery, 'AND')
//                    ->withFilter(['post.status_i' => 10], 'AND')
//                    ->getFacet();
//            default:
//                throw new ForbiddenHttpException("not found the page");
//        }
//    }

    /**
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function withFacet()
    {
        $solrQuery = $this->convertModelToSolrQuery();
        $count = count($solrQuery);
        $query = Solr::find('posts_new')
            ->useFacet()
            ->setQuery($solrQuery, 'AND')
            ->withFilter(['post.status_i' => 10], 'AND');
        return $this->prepare($query, $solrQuery);
    }

    /**
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function prepareQuery(int $count, $query)
    {
        switch ($count) {
            case 1 :
                return $query->termFacet('categories', 'category.label_en_s', 0)
                    ->nestedTermFacet('categoryId', 'post.category_id_i', 0)
                    ->getFacet();
            case 2 :
                return $query->termFacet('categories', 'subcategories.label_en_s', 0)
                    ->nestedTermFacet('categoryId', 'post.subcategory_id_i', 0)
                    ->getFacet();
            case 3 :
                return $query->getFacet();
            default:
                throw new ForbiddenHttpException("not found the page");
        }
    }
    public function prepare($query, $solrQuery)
    {
        $keys = ['post.subcategory_id_i' => 'subcategories.label_en_s', 'post.category_id_i' => 'category.label_en_s'];
        $key = '';
        $keyValue = '';
        dd($solrQuery);

//        foreach ($keys as $item => $value) {
//            if (dd(empty($solrQuery)) {
//                $key = $item;
//                $keyValue = $value;
//            }}
//        dd($solrQuery->item);
//            return $query->termFacet('categories', $keyValue, 0)
//                ->nestedTermFacet('categoryId', $key, 0)
//                ->getFacet();

        //        $i=0;
//        $i++;
//        $arrayQuery=[0=>['post.subcategory_id_i' => 'subcategories.label_en_s'],1=>['post.category_id_i' => 'category.label_en_s'], 2=>[' '=> ' ']];
//        dd($arrayQuery[$i][]);
//        if (!empty($arrayQuery[$i])) {
//            return $query->termFacet('categories', $arrayQuery[$i++], 0)
//                ->nestedTermFacet('categoryId', $arrayQuery[$i++], 0)
//                ->getFacet();
//        } else {
//            return $query->getFacet();
//        }
    }


//    public function withFacet()
//    {
//        $solrQuery = $this->convertModelToSolrQuery();
//        $key = '';
//        $field = '';
//        $keys = ['post.subcategory_id_i' => 'subcategories.label_en_s','post.category_id_i' => 'category.label_en_s'];
//        foreach ($keys as $item => $itemField) {
//            if (empty($solrQuery->$item)) {
//                $key = $item;
//                $field = $itemField;
//            }
//        }
//        return Solr::find('posts_new')
//            ->useFacet()
//            ->setQuery($solrQuery, 'AND')
//            ->withFilter(['post.status_i' => 10], 'AND',)
//            ->termFacet('categories', $field, 0)
//            ->nestedTermFacet('categoryId', $key, 0)
//            ->getFacet();
//    }


    /**
     * @throws Exception
     */
    public function withFacetSubcategory()
    {
        $solrQuery = $this->convertModelToSolrQuery();
        return Solr::find('posts_new')
            ->useFacet()
            ->setQuery($solrQuery, 'AND')
            ->withFilter(['post.status_i' => 10], 'AND')
            ->termFacet('categories', 'subcategories.label_en_s', 0)
            ->nestedTermFacet('categoryId', 'subcategories.id_i', 0)
            ->getFacet();
    }

    private function convertModelToSolrQuery(): array
    {
        $solrQuery = [];
        $attributes = $this->attributes;
        foreach ($attributes as $attribute => $value) {
            if (empty($value)) continue;
            $type = Field::getValueTypeForSolr($value);
            $solrQuery["post.{$attribute}_{$type}"] = $value;
        }
        return $solrQuery;
    }

    /**
     * @throws Exception
     */
    public static function findByCategorySearch($id)
    {
        return Solr::find('posts_new')
            ->useFacet()
            ->setQuery([], 'AND')
            ->withFilter(['post.status_i' => 10, 'post.category_id_i' => $id], 'AND')
            ->termFacet('categories', 'subcategories.label_en_s', 0)
            ->nestedTermFacet('categoryId', 'subcategory.id_i', 0)
            ->getFacet();
    }

    /**
     * @throws Exception
     */
    public static function findPostByCategoryId($id)
    {
        return Solr::find('posts_new')
            ->useFacet()
            ->setQuery([], 'AND')
            ->withFilter(['post.status_i' => 10, 'post.category_id_i' => $id], 'AND')
            ->sort('id desc')
            ->termFacet('categories', 'subcategories.label_en_s', 0)
            ->nestedTermFacet('categoryId', 'subcategories.id_i', 0)
            ->getFacet();
    }
}