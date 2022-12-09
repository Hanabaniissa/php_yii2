<?php

namespace app\modules\api\modules\v1\controllers;

use app\modules\api\models\Post;
use yii\helpers\Json;
use yii\rest\Controller;

class SolrController extends Controller
{
    public static function actionTest()
    {
        $post = Post::find()->where(['id'=>87])->one();

        $id=$post->id;



        $posts = Post::find()->all();

        $temp_posts = [];
            $temp_post= [
                'id' => $post->id,
                'title_s' => $post->title,
                'desc_s' => $post->description,
                'phone_i'=>$post->phone,
                'user_id_i'=>$post->user_id,
                'category_id_i'=>$post->category_id,
                'created_at_s'=>$post->created_at,
                'created_by_i'=>$post->created_by,
                'updated_at_s'=>$post->updated_at,
                'updated_by_i'=>$post->updated_by,
                'post_image_s'=>$post->post_image,
                'status_i'=>$post->status,
                'city_id_i'=>$post->city_id,
                'subCategory_id_i'=>$post->subCategory_id,
                'neighborhood_id_i'=>$post->neighborhood_id,
                'price_i'=>$post->price
            ];
        echo Json::encode($temp_post); die;

        $posts_json = Json::encode($temp_post);

        $url = "http://localhost:8983/solr/dynamic_field/update/json/docs?commit=true";
        $ch = curl_init();
        $header = array('Content-Type: application/json');

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts_json);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

        $data = curl_exec($ch);
        $return = 0;
        if (!curl_errno($ch)) {
            $return = $data;
        }
        curl_close($ch);
        return $return;
    }








     public function actionGet(){

         $url = "http://localhost:8983/solr/test_dynamic/query?q=*:*&q.op=OR&indent=true&rows=1000";
         $ch = curl_init();
         $header = array('Content-Type: application/json');

         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

         curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
         curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
         $data = curl_exec($ch);
         $return = 0;
         if (!curl_errno($ch)) {
var_dump($data);die;
             $return = $data;
         }
         curl_close($ch);
         return $return;

     }
}

