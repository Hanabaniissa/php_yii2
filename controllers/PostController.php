<?php

namespace app\controllers;

use app\helpers\CountryUtils;
use app\models\Assign;
use app\models\post;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\UploadedFile;


class PostController extends Controller
{

    const RECENTLY_VIEWED_COOKIE = 'recentlyViewed';

    public function actionPost($postId = null)
    {
        $post = new post();
        if (!empty($postId)) {
            $post = post::find()->where(['id' => $postId])->one();

        }
       $assignModels=Assign::getAssignWithField(1)->all();


        if (Yii::$app->request->isPost) {
            $post->load(yii::$app->request->post());
            $post->load(yii::$app->request->post(), '');



            if ($imageModel = UploadedFile::getInstance($post, 'post_image')) {
                $fileName = time() . "-" . $imageModel->name;
                $imageModel->saveAs(Yii::$app->basePath . Yii::getAlias('@upload') . "/{$fileName}");
                $post->post_image = $fileName;


            }

            if ($post->validate()) {
                $post->save();
                $categoryId = $post->category_id;
                return $this->redirect(['post/view-by-category', 'id' => $categoryId]);

            } else {
                var_dump($post->errors);
                die("not valid");
            }
        }
        return $this->render('create_post', ['post' => $post, 'country_id' => CountryUtils::getPreferredCountry(),'assigns'=>$assignModels]);
    }


    public function actionDelete($postId)
    {
        $post = post::find()->where(['id' => $postId])->one();
        $categoryId = $post->category_id;
        $post->delete();
        return $this->redirect(['post/view-by-category', 'id' => $categoryId]);
    }


    public function actionViewOne($id)
    {

        //  $userId = \Yii::$app->user->id;
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
        return $this->render('view_post', ['onePost' => $onePost]);
    }


    public function actionRecentlyViewed()
    {


//        if(Yii::$app->user->isGuest) return '';
        $cookies = Yii::$app->request->cookies;
        if (!$cookies->has(self::RECENTLY_VIEWED_COOKIE)) return '';
        $postsIds = $cookies[self::RECENTLY_VIEWED_COOKIE]->value;
        if (!is_array($postsIds)) $postsIds = [$postsIds];

        // [1, 2, 3]
        // 1,2,3 explode(',', $str) => [1, 2, 3]
//        if(\Yii::$app->user->id) return'';
        $query = post::find()->where(['id' => $postsIds])->andWhere(['status' => 10]);
        $posts = [];
        $postsIds = array_reverse($postsIds);
        foreach ($postsIds as $postsId) {
            $posts[] = post::findOne($postsId);
        }

        // DaraProvider
        // ActiveDataProvider
        // ArrayDataProvider
        $countQuery = clone $query;
        $recentlyPages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 4]);
//        $posts = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 4
//            ],
//        ]);

        $result = new ArrayDataProvider([
            'allModels' => $posts,
            'pagination' => [
                'pageSize' => 4
            ],
        ]);
        return $this->render('recent', ['posts' => $result, 'recentlyPages' => $recentlyPages]);
    }


    public function actionViewByCategory($id)
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


    public function actionViewMyPost()
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


    public function actionSearch($term = '')
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
}