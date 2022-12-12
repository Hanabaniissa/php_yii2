<?php


namespace app\controllers;

use app\models\Favorite;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class FavoriteController extends Controller
{


    public function actionSet($postId)
    {
        $userId = \Yii::$app->user->id;
        $favPost = new Favorite();
        $favPost->user_id = $userId;
        $favPost->post_id = $postId;
        $favPost->save();
        return $this->redirect(['favorite/get']);
    }

    public function actionGet()
    {
        $query = Favorite::getFavoritePostQuery();
        $models = new ActiveDataProvider(
            ['query' => $query,
                'pagination' => [
                    'pageSize' => 4
                ]
            ]
        );
        return $this->render('favorite_post', ['models' => $models]);
    }
}

