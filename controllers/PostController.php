<?php

namespace app\controllers;

use app\components\solr\Solr;
use app\helpers\CountryUtils;
use app\models\post;
use app\models\PostValue;
use Psy\Util\Json;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\web\UploadedFile;


class PostController extends Controller
{

    const RECENTLY_VIEWED_COOKIE = 'recentlyViewed';

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws Throwable
     */
    public function actionPost($postId = null)
    {
        $post = new post();
        if (!(empty($postId) && empty($post->value))) {
            $post = post::find()->where(['id' => $postId])->one();
            foreach ($post->value as $item) {
                $item->delete();
            }
        }
        $post->country_id = CountryUtils::getPreferredCountry();

        if (Yii::$app->request->isPost) {
            $postFields = Yii::$app->request->post('PostField') ?? [];
            $post->load(Yii::$app->request->post());
            $post->load(Yii::$app->request->post(), '');

            if ($imageModel = UploadedFile::getInstance($post, 'post_image')) {
                $fileName = time() . "-" . $imageModel->name;
                $imageModel->saveAs(Yii::$app->basePath . Yii::getAlias('@upload') . "/{$fileName}");
                $post->post_image = $fileName;
            }
            if ($post->validate() && $post->save()) {
                $postID = $post->id;
                foreach ($postFields as $field => $option) {
                    $postValue = new PostValue();
                    $postValue->post_id = $post->id;
                    $postValue->field_id = $field;
                    $postValue->option_id = $option;
                    if (!$postValue->save()) {
                        echo Json::encode($postValue->errors);
                        die;
                    }
                }
                $models = [
                    'app\models\post' => $post->id,
                    'app\models\Country' => $post->country_id,
                    'app\models\City' => $post->city_id,
                    'app\models\Neighborhood' => $post->neighborhood_id,
                    'app\models\Category' => $post->category_id,
                    'app\models\SubCategories' => $post->subCategory_id,
                ];
                $temp_model['id'] = $post->id;
                Solr::find('posts_new')->useDocument()->save($models, $temp_model);
                return $this->redirect(['post/view-one', 'id' => $postID]);
            } else {
                var_dump($post->errors);
                die("not valid");
            }
        }
        return $this->render('create_post', ['post' => $post, 'country_id' => CountryUtils::getPreferredCountry()]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($postId): Response
    {
        $post = post::find()->where(['id' => $postId])->one();
        $categoryId = $post->category_id;
        $post->delete();
        Solr::find('test_dynamic')->useDocument()->from($postId)->update();
        return $this->redirect(['post/view-by-category', 'id' => $categoryId]);
    }

    public function actionViewOne($id)
    {
        $cookies = Yii::$app->response->cookies;
        $currentCookies = Yii::$app->request->cookies;
        $recentlyViewedPosts = [];
        if (!empty($currentCookies[self::RECENTLY_VIEWED_COOKIE]))
            $recentlyViewedPosts = $currentCookies[self::RECENTLY_VIEWED_COOKIE]->value;
        if (!is_array($recentlyViewedPosts)) $recentlyViewedPosts = [$recentlyViewedPosts];

        // Remove from Array
        $key = array_search($id, $recentlyViewedPosts);
        if (false !== $key) {
            unset($recentlyViewedPosts[$key]);
        }
        $recentlyViewedPosts[] = $id;
        $recentlyViewedPosts = array_unique($recentlyViewedPosts);
        $cookies->add(new Cookie([
            'name' => self::RECENTLY_VIEWED_COOKIE,
            'value' => $recentlyViewedPosts,
            'httpOnly' => true,
            'expire' => time() + 60 * 60 * 24,
            'sameSite' => Cookie::SAME_SITE_STRICT
        ]));
        $onePost = post::findOne($id);
        return $this->render('view_post', ['onePost' => $onePost,]);
    }

    public function actionRecentlyViewed(): string
    {
        $cookies = Yii::$app->request->cookies;
        if (!$cookies->has(self::RECENTLY_VIEWED_COOKIE)) return '';
        $postsIds = $cookies[self::RECENTLY_VIEWED_COOKIE]->value;

        if (!is_array($postsIds)) $postsIds = [$postsIds];
        $query = post::find()->where(['id' => $postsIds])->andWhere(['status' => 10]);
        $posts = [];
        $postsIds = array_reverse($postsIds);
        foreach ($postsIds as $postsId) {
            $posts[] = post::findOne($postsId);
        }

        $result = new ArrayDataProvider([
            'allModels' => $posts,
            'pagination' => [
                'pageSize' => 4
            ],
        ]);
        return $this->render('recent', ['posts' => $result]);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */

    //TODO
    public function actionViewByCategory($id): string
    {
//        $solrData = \app\models\solr\Post::findPostByCategoryId($id);
//        $posts = [];
//        $facetFields = \app\models\solr\Post::prepareFacetFields($solrData);
//        foreach ($solrData->response->docs as $postArray) {
//            $posts[] = \app\models\solr\Post::getPost($postArray);
//        }
//        $posts = new ArrayDataProvider([
//            'allModels' => $posts,
//            'pagination' => [
//                'pageSize' => 4
//            ]
//        ]);

        $query = post::findPostByCategoryIdQuery($id);
        $posts = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );
        $facetFields = \app\models\solr\Post::getFacetFields($id);
        $route = 'post/view-by-sub-category';

        return $this->render('category', ['posts' => $posts, 'facetFields' => $facetFields, 'route' => $route]);
    }

    /**
     * @throws Exception
     */
    public function actionViewSearchByCategory($id)
    {
        $solrData = \app\models\solr\Post::findByCategory($id);
        $posts = [];
        $facetFields = \app\models\solr\Post::prepareFacetFields($solrData);
        foreach ($solrData->response->docs as $postArray) {
            $posts[] = \app\models\solr\Post::getPost($postArray);
        }
        $posts = new ArrayDataProvider([
            'allModels' => $posts,
            'pagination' => [
                'pageSize' => 4
            ]
        ]);
        $route = 'post/view-by-sub-category';
        return $this->render('category', ['posts' => $posts, 'facetFields' => $facetFields, 'route' => $route]);
    }

    //TODO
    public function actionViewBySubCategory($id): string
    {
//        $queryBySolr =
        $query = post::findPostBySubCategoryIdQuery($id);
        $posts = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );
        return $this->render('subCategory', ['posts' => $posts]);
    }

    public function actionViewMyPost(): string
    {
        $query = post::findMyPostQuery();
        $myPost = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );
        return $this->render('myPosts', ['myPost' => $myPost]);
    }

    /**
     * @throws Exception
     */

    //TODO
    public function actionSearch($term = ''): string
    {
        $solrData = \app\models\solr\Post::withFacet($term);
        $posts = [];
        $facetFields = \app\models\solr\Post::prepareFacetFields($solrData);

        foreach ($solrData->response->docs as $postArray) {
            $posts[] = \app\models\solr\Post::getPost($postArray);
        }
        $posts = new ArrayDataProvider([
            'allModels' => $posts,
            'pagination' => [
                'pageSize' => 4
            ]
        ]);
//        $facetFields = \app\models\solr\Post::getFacetFieldsSearch($term);
        $route = 'post/view-search-by-category';
        return $this->render('category', ['posts' => $posts, 'facetFields' => $facetFields, 'route' => $route]);
    }

    /**
     * @throws \Exception
     */

    public function actionGetDoc()
    {
        $docs = Solr::find('posts_new')->useFacet()
            ->setQuery(['country.label_en_s' => 'Jordan', 'city.label_en_s' => 'Irbid'], 'AND')
            ->withFilter(['country.label_en_s' => 'Jordan', 'city.label_en_s' => 'Irbid'], 'OR')
            ->termFacet('hana', 'city.label_en_s', 4)
            ->nestedTermFacet('city', 'ff', 2)
            ->getFacet();
        dd($docs);


//        $docs = Solr::find('test_dynamic')->useQuery()->query(['id_post_i' => 176])->get();
//        dd($docs);
//        $data = rawurlencode(" : ");
//        dd($data);
//        die;
    }


}