<?php

namespace app\controllers;

use app\components\solr\Documents;
use app\helpers\CountryUtils;
use app\models\post;
use app\models\PostValue;
use Psy\Util\Json;
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

                $id = $post->id;
                $model = 'app\models\post';
                $modelName = str_replace('app\models\\', '', strtolower($model));

                $dataConfigParams = [
                    'id' => $id,
                    'model' => $model,
                    'core' => 'test_dynamic',
                    'modelName' => $modelName,
                ];

                Documents::create($dataConfigParams);
                return $this->redirect(['post/view-one', 'id' => $postID]);

            } else {
                var_dump($post->errors);
                die("not valid");
            }
        }
        return $this->render('create_post', ['post' => $post, 'country_id' => CountryUtils::getPreferredCountry()]);
    }


    public function actionDelete($postId)
    {
        $post = post::find()->where(['id' => $postId])->one();

        $categoryId = $post->category_id;
        $post->delete();

//        $temp_post=[
//            'id_i'=>$post->id,
//            'status_i'=>$post->status
//        ];
//        $posts_json = \yii\helpers\Json::encode($temp_post);
//
//        $url = "http://localhost:8983/solr/dynamic_field/update/json/docs?commit=true";
//        $ch = curl_init();
//        $header = array('Content-Type: application/json');
//
//        curl_setopt($ch, CURLOPT_URL, $url);
//
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

//        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts_json);
//
//        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//
//        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
//
//        $data = curl_exec($ch);
//        $return = 0;
//        if (!curl_errno($ch)) {
//            $return = $data;
//        }
//        curl_close($ch);

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
//        $values = $onePost->value;

//        $solrAttributes = [];
//        foreach ($values as $value) {
//            $field = $value->field;
//            $option = $value->option;
//            $key = str_replace(' ', '_', strtolower($field->label_en));
//            $key .= "_" . $this->getFiledTypeForSolr($field);
//            $solrAttributes[$key] = $option->label_en;
//        }
//        echo Json::encode($solrAttributes); die;
        return $this->render('view_post', ['onePost' => $onePost,]);
    }

//    private function getFiledTypeForSolr(Field $field) {
//        switch ($field->type) {
//            case 'Int': return 'i';
//            case 'String': return 's';
//        }
//    }

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


    public function actionPostKeys()
    {
//        $posts= new \app\modules\api\models\Post();
        $posts = \app\modules\api\models\Post::find()->where(['id' => '108'])->one();
        foreach ($posts as $post => $value) {
            $key = $post;
            $key_value = $value;
            $hh = 'hh';
//            $type=gettype($hh);
            $type = gettype($value);


//            echo Json::encode($type);die;


            $field = str_replace('', '_', strtolower($key));


            $field .= "_" . $this->getPostFieldTypeForSolr($type);
            $attributes[$field] = $key_value;

        }
        echo Json::encode($attributes);


    }

//    private function getPostFieldTypeForSolr($field){
//        switch($field){
//            case 'integer': return 'i';
//            break;
//            case 'string': return 's';
//            break;
//            default: return 'null';
//        }
//
//
//    }

}