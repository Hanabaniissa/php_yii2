<?php

namespace app\controllers;

use app\components\solr\Document;
use app\components\solr\Field;
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
use yii\data\Pagination;
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
     */

    public function actionPost($postId = null)
    {
        $post = new post();
        if (!empty($postId)) {
            $post = post::find()->where(['id' => $postId])->one();
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

//                $id = $post->id;
//                $model = 'app\models\post';
//                $modelName = str_replace('app\models\\', '', strtolower($model));


                $models = [
                    'app\models\post' => $post->id,
                    'app\models\Country' => $post->country_id,
                    'app\models\City' => $post->city_id,
                    'app\models\Neighborhood' => $post->neighborhood_id,
                    'app\models\Category' => $post->category_id,
                    'app\models\SubCategories' => $post->subCategory_id,
                ];


                $temp_model['id'] = $post->id;
//                Solr::coreDocument('test_dynamic')->create($temp_model);

                Solr::find('test_dynamic')->useDocument()->save($models, $temp_model);
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
        \app\components\solr\Solr::coreDocument('test_dynamic')->from($postId)->update();

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
//     $recentlyViewedPosts[] = ['id' => $id, 'actionDate' => date('Y-m-d')];

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

        $countQuery = clone $query;
        $recentlyPages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 4]);

        $result = new ArrayDataProvider([
            'allModels' => $posts,
            'pagination' => [
                'pageSize' => 4
            ],
        ]);
        return $this->render('recent', ['posts' => $result, 'recentlyPages' => $recentlyPages]);
    }


    public function actionViewByCategory($id): string
    {
        $query = post::findPostByCategoryIdQuery($id);
        $posts = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );

        return $this->render('category', ['posts' => $posts]);
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


    public function actionSearch($term = ''): string
    {
        $query = post::search($term);
        $posts = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );
        return $this->render('category', ['posts' => $posts]);
    }


    /**
     * @throws Exception
     * @throws \Exception
     */

    public function actionGetDoc()
    {
//        $fields = [
//            "author_s" => "hello",
//            "ggg" => "fff"
//        ];
//        $data = [
//            "id" => "book1",
//            "ggg" => "fff"
//        ];

        // {"id"         : "book1",
//  "author_s"   : {"set":"Neal Stephenson"},
//  "copies_i"   : {"inc":3},
//  "cat_ss"     : {"add":"Cyberpunk"}
// }
//        $doc = Solr::find('core_docs')->useDocument()->from(1)->set(['author_s' => 'NealLn', 'cat_ss' => 'hello'])->update();
//        var_dump($doc);
//        die;
//        $data = [
//            "id" => 410,
//            "title_post_s" => "Hello world!",
//        ];

        $docs = Solr::find('test_dynamic')->useQuery()->page(3)->getStart();
        var_dump($docs);
        die;

//        Document::delete($data);
//        $docs = Solr::core('test_dynamic')->get();

        var_dump($docs);
        die;

    }


}