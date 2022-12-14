<?php

namespace app\models\solr;

use app\components\solr\Solr;
use Yii;
use yii\base\Model;
use yii\db\Exception;

class Post extends Model
{
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


    public function rules(): array
    {
        return [
            [['id', 'created_at', 'updated_at', 'user_id', 'created_by'], 'safe'],
            [['title', 'description', 'phone', 'category_id', 'country_id', 'subcategory_id', 'city_id', 'neighborhood_id', 'price'], 'required'],
            [['status', 'created_by', 'updated_by', 'phone'], 'integer'],
            [['title', 'description'], 'string', 'max' => 300],
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
     */
//    public static function getFacetFieldsSearch($term)
//    {
//        $facetFields = Solr::find('posts_new')->useFacet()->queryFacet(['post.title_s' => "*$term*"])->name('categories', 'terms', 'category.label_en_s')->getFacet();
//        return self::prepareFacetFields($facetFields);
//    }

    //TODO
//    public static function prepareFacetFieldSearch($data)
//    {
//        if (!empty($data->facets->count)) {
//            $field = [];
//            $dataFacet = $data->facets->categories->buckets;
//            foreach ($dataFacet as $item) {
//                $id = $item->id->buckets[0]->val;
//                $field[$id] = $item->val;
//            }
//            return $field;
//        } else return 0;
//    }
    /**
     * @throws Exception
     * @throws \Exception
     */
    public static function getFacetFields($category_id)
    {
        $facetFields = Solr::find('posts_new')
            ->useFacet()
            ->setQuery(['category.id_i' => $category_id], 'AND')
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
     */
    public static function withFacet($term)
    {
        return Solr::find('posts_new')
            ->useFacet()
            ->setQuery(['post.title_s' => "*$term*"], 'And')
            ->withFilter(['post.status_i' => 10], 'AND')
            ->termFacet('categories', 'category.label_en_s', 0)
            ->nestedTermFacet('categoryId', 'category.id_i', 0)
            ->getFacet();
    }

    /**
     * @throws Exception
     */
    public static function findByCategory($id)
    {
        return Solr::find('posts_new')
            ->useFacet()
            ->setQuery([], 'ANd')
            ->withFilter(['post.status_i' => 10,'post.category_id_i'=>$id], 'AND')
            ->termFacet('categories', 'subcategories.label_en_s', 0)
            ->nestedTermFacet('categoryId', 'subcategories.id_i', 0)
            ->getFacet();
    }
}