<?php

namespace app\controllers;

use app\models\Category;
use app\models\post;
use app\models\SignupForm;
use Yii;

use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;
use yii\web\User;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $categoryModels = Category::find()->all();
        return $this->render('home', ['categories' => $categoryModels]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionPost($postId = null)
    {
        $post = new post();
        if (!empty($postId)) {
            $post = post::find()->where(['id' => $postId])->one();
        }

        if (Yii::$app->request->isPost) {
            $post->load(yii::$app->request->post());
            $post->load(yii::$app->request->post(), '');

            $imageModel = UploadedFile::getInstance($post, 'post_image');
            $fileName = time() . "-" . $imageModel->name;
            $imageModel->saveAs(Yii::$app->basePath . Yii::getAlias('@upload') . "/{$fileName}");
            $post->post_image = $fileName;
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


    public
    function actionSignUp()
    {
        $model = new SignupForm();
        if ($model->load(yii::$app->request->post()) && $model->signup()) {
            $this->redirect(Yii::$app->homeUrl);
        }
        return $this->render('signup',
            ['model' => $model]);
    }

    public
    function actionView($id)
    {
        $posts = post::findPostByCategoryId($id);
        return $this->render('category', ['posts' => $posts]);
    }

    public
    function actionViewPost($id)
    {
        $onePost = post::findOnePost($id);
        return $this->render('view_post', ['onePost' => $onePost]);
    }

    public
    function actionDeletePost($postId)
    {
        $deletepost = post::find()->where(['id' => $postId])->one();
        $deletepost->delete();
        $categoryModels = Category::find()->all();
        return $this->render('home', ['categories' => $categoryModels]);
    }


}
