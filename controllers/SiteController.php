<?php

namespace app\controllers;

use app\helpers\CountryUtils;
use app\models\Category;
use app\models\Country;
use app\models\SignupForm;
use app\models\SubCategories;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;


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
    public function actionIndex($countryId = null)
    {
        if (empty($countryId)) {
            $countryId = $this->getPreferredCountry();
            if (empty($countryId))
                return $this->redirect(Url::to(['country/get']));
        } else {
            $this->setPreferredCountry($countryId);
        }

        //$subCategoriesModels= SubCategories::find()->where(['country_id' => $countryId])->all();
        $subCategoriesModels = SubCategories::getSubCategories($countryId, true);


        $countriesModels = Country::find()->where(['id' => $countryId])->one();
        $categoryModels = Category::getCategoriesBy($countryId, true);
        //   $subCategoriesModels = SubCategories::getSubCategories($countryId,true);
        return $this->render('home', ['categories' => $categoryModels, 'country' => $countriesModels]);
    }

    public function actionTest()
    { $countryId=CountryUtils::getPreferredCountry();
        Yii::$app->response->format = 'json';
        return Category::getCategoriesBy($countryId,true);
    }

    public function actionSub()
    {$countryId=CountryUtils::getPreferredCountry();
        Yii::$app->response->format = 'json';
        if(isset($_GET['category'])) {
            $categoryId = $_GET['category'];

            return SubCategories::getSubCategories($countryId, $categoryId, true);
        }
        return null;
    }

    private function setPreferredCountry($countryId)
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new Cookie([
            'name' => 'country',
            'value' => $countryId,
            'httpOnly' => true,
            'expire' => time() + 60 * 60 * 24,
            'sameSite' => Cookie::SAME_SITE_STRICT
        ]));
    }

    private function getPreferredCountry()
    {
        $currentCookies = Yii::$app->request->cookies;
        if (!empty($currentCookies['country']->value)) return $currentCookies['country']->value;
        return null;
    }

    /**
     * Login action.
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
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
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
     * Displays about pages
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionSignUp()
    {
        $model = new SignupForm();
        if ($model->load(yii::$app->request->post()) && $model->signup()) {
            $this->redirect(Yii::$app->homeUrl);
        }
        return $this->render('signup',
            ['model' => $model]);
    }
}
