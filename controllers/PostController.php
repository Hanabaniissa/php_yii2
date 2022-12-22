<?php

namespace app\controllers;

use app\commands\SolrPostJob;
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
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class PostController extends Controller
{
    const RECENTLY_VIEWED_COOKIE = 'recentlyViewed';
    const CHANNEL_REDIS = 'posts_solr';

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
                $id = Yii::$app->queue->push(new SolrPostJob([
                    'models' => $models,
                    'temp_model' => $temp_model
                ]));

                $message = [
                    'models' => $models,
                    'temp_model' => $temp_model
                ];
//                Yii::$app->redis->publish(self::CHANNEL_REDIS, serialize($message));
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
        Solr::find('posts_new')->useDocument()->from($postId)->set(["post.status_i" => 0])->update();
        $post->delete();
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


    public function actionViewByCategory($id): string
    {
        $solrData = \app\models\solr\Post::findPostByCategoryId($id);
        $posts = [];
        $facetFields = \app\models\solr\Post::prepareFacetFields($solrData);
        foreach ($solrData->response->docs as $postArray) {
            $posts[] = \app\models\solr\Post::getPost($postArray);
        }
        $posts = new ArrayDataProvider([
            'allModels' => $posts,
            'pagination' => [
                'pageSize' => 4
            ]]);
//        $query = post::findPostByCategoryIdQuery($id);
//        $posts = new ActiveDataProvider([
//                'query' => $query,
//                'pagination' => [
//                    'pageSize' => 4
//                ]
//            ]
//        );
//        $facetFields = \app\models\solr\Post::getFacetFields($id);
//        $route = 'post/view-by-sub-category';
        return $this->render('category', [
            'posts' => $posts,
            'facetFields' => $facetFields,
        ]);
    }

    /**
     * @throws Exception
     */
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
     * @throws ForbiddenHttpException
     */
    //TODO
    public function actionSearch(): string
    {
        $postSearch = Yii::$app->request->get('PostSearch');
        $postSearchModel = new \app\models\solr\Post();
        $postSearchModel->setScenario(\app\models\solr\Post::SCENARIO_SEARCH);
        $postSearchModel->load($postSearch, '');
        $postSearchModel->validate();
        $solrData = $postSearchModel->withFacet($postSearchModel);
        if (empty($solrData->response->docs)) {
            return throw new ForbiddenHttpException("not found the page");
        }
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
        $route = 'post/search';
        $keys = ['subcategory_id', 'category_id'];
        $key = '';
        foreach ($keys as $item) {
            if (empty($postSearchModel->$item)) {
                $key = $item;
            }
        }
        return $this->render('category_search', [
            'posts' => $posts,
            'facetFields' => $facetFields,
            'key' => $key,
            'params' => $postSearch,
        ]);
    }

    public function actionDo()
    {
//        $message = 'ar';
//        $id = Yii::$app->queue->push(new SolrPostJob(
//            serialize($message)));
//        dd($id);
//        dd(Yii::$app->queue->isWaiting($id));
    }



//    public function actionGetDoc()
//    {
//        $url = "https://apiv.sooqdev2.com/vertical/forms/v1/add-post/normal/1?expand=remaining_edit_counter%2Cmedia&abBucket=3";
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        $username = '0700110022';
//        $password = '123456';
//        $header = [
//            'host: apiv.sooqdev2.com',
//            'x-real-ip: 194.165.141.170',
//            'connection: close',
////            'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjYzODI2MDYyLCJhdDAiOjE2NzEzNDc0MjcsImV4cCI6MTY3MTM2NTM4NSwiYXVkIjoiYW5kcm9pZCIsInJuZCI6IjY3MTE5ODA2In0.6UzAYWw3piTpSfRIDH-4-lnvDRLa5m5zfTnITFg7XdY',
//            'abbucket: 3',
//            'source: android',
//            'appversion: 355',
//            'always-200: 1',
//            'uuid: b0e89b32-954d-4462-a7c5-0aea9aa9cdb9',
//            'x-tracking-uuid: _dced576a-3407-4f8f-bfe8-61638e3c0526',
//            'connectiontype: WIFI',
//            'user-agent: OpenSooq/355/v2.1/3 (Android-10/HUAWEI,STK-L21)',
//            'country: jo',
//            'accept-language: ar',
//            'display-mode: normal',
//            'release-version: 9.4.00',
//            'accept-encoding: gzip',
//            'if-modified-since: Sun, 18 Dec 2022 11:35:54 GMT',
//            'session-id: 23a308f9-86c3-4cb3-aa91-5d3a85a76cb4'
//        ];
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
//        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//        $data = curl_exec($ch);
//        $return = 0;
//        if (!curl_errno($ch)) {
//            print_r($data);
//            die;
//            $return = $data;
//        }
//        curl_close($ch);
//        return $return;
//    }


//curl_setopt($ch, CURLOPT_HEADER,'host: apiv.sooqdev2.com');
//        curl_setopt($ch, CURLOPT_HEADER,'x-real-ip: 194.165.141.170');
//        curl_setopt($ch, CURLOPT_HEADER,'connection: close');
//        curl_setopt($ch, CURLOPT_HEADER,'content-length: 535');
//        curl_setopt($ch, CURLOPT_HEADER,'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjYzODI2MDYyLCJhdDAiOjE2NzEzNDc0MjcsImV4cCI6MTY3MTM1NzgzMywiYXVkIjoiYW5kcm9pZCIsInJuZCI6IjQzNjI3OTIwIn0.l1SkIMUvwr_JS-eZH7xJdJHZrlHVhJHiJcnV8TiQkpI');
//        curl_setopt($ch, CURLOPT_HEADER,'abbucket: 3');
//        curl_setopt($ch, CURLOPT_HEADER,'source: android');
//        curl_setopt($ch, CURLOPT_HEADER,'appversion: 355');
//        curl_setopt($ch, CURLOPT_HEADER,'always-200: 1');

////
//curl_setopt($ch, CURLOPT_HEADER,'uuid: b0e89b32-954d-4462-a7c5-0aea9aa9cdb9');
//curl_setopt($ch, CURLOPT_HEADER,'x-tracking-uuid: _dced576a-3407-4f8f-bfe8-61638e3c0526');
//curl_setopt($ch, CURLOPT_HEADER,'connectiontype: WIFI');
//curl_setopt($ch, CURLOPT_HEADER,'user-agent: OpenSooq/355/v2.1/3 (Android-10/HUAWEI,STK-L21)');
//curl_setopt($ch, CURLOPT_HEADER,'country: jo');
//curl_setopt($ch, CURLOPT_HEADER,'accept-language: ar');
//curl_setopt($ch, CURLOPT_HEADER,'session-id: 23a308f9-86c3-4cb3-aa91-5d3a85a76cb4');
//curl_setopt($ch, CURLOPT_HEADER,'display-mode: normal');
//curl_setopt($ch, CURLOPT_HEADER,'release-version: 9.4.00');
////        curl_setopt($ch, CURLOPT_HEADER,'content-type: application/x-www-form-urlencoded');
//curl_setopt($ch, CURLOPT_HEADER,'accept-encoding: gzip');
//curl_setopt($ch, CURLOPT_HEADER,'if-modified-since: Sun, 18 Dec 2022 11:35:54 GMT');


//https://sy.opensooq.com/ar/find?
//PostSearch[categoryId]=13179&PostSearch[subCategoryId]=2331&
//PostSearch[dynamicAttributes][Make_Motorbike][0]=4531&
//PostSearch[dynamicAttributes][Make_Motorbike_child][0]=8690&
//PostSearch[dynamicAttributes][Kilometers_Cars][0]=4601
}