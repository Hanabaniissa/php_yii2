<?php

namespace app\controllers;

use app\models\post;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;


class PostController extends Controller
{


    public function actionPost($postId = null)
    {
        $post = new post();
        if (!empty($postId)) {
            $post = post::find()->where(['id' => $postId])->one();
        }

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
                return $this->redirect(Yii::$app->homeUrl);
            } else {
                var_dump($post->errors);
                die("not valid");
            }
        }
        return $this->render('create_post', ['post' => $post]);
    }


    public function actionDelete($postId)
    {
        $post = post::find()->where(['id' => $postId])->one();
        $post->delete();
        return $this->redirect(['site/index']);
    }

    public function actionViewOne($id)
    {
        $onePost = post::findOnePost($id);
        return $this->render('view_post', ['onePost' => $onePost]);
    }

    public function actionViewByCategory($id)
    {
        $query = post::findPostByCategoryIdQuery($id);
        $countQuery = clone $query;
        $pagesPost = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 4]);
        $posts = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );

        return $this->render('category', ['posts' => $posts, 'pagesPost' => $pagesPost]);
    }

    public function actionViewMyPost()
    {
        $query = post::findMyPostQuery();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 4]);
        $myPost = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );
        return $this->render('myPosts', ['myPost' => $myPost, 'pages' => $pages]);
    }


    public function actionSearch($term = ''){
        // TODO: QUERY
        $query = post::find();
        $countQuery = clone $query;
        $pagesPost = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 4]);
        $posts = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );
        return $this->render('category', ['posts' => $posts, 'pagesPost' => $pagesPost]);
    }
}