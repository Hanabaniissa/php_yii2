<?php

namespace app\modules\api\modules\v1\controllers;

use app\models\LoginForm;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

class RestController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login',
            ]
        ];
        return $behaviors;
    }

    private function generateJWT(\app\models\User $user)
    {

        /** @var Jwt $jwt */
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        $jwtParams = Yii::$app->params['jwt'];
        $tokenObject = $jwt->getBuilder()
            ->issuedBy($jwtParams['issuer'])
            ->permittedFor($jwtParams['audience'])
            ->identifiedBy($jwtParams['id'], true)
            ->issuedAt($time)
            ->expiresAt($time + $jwtParams['expire'])
            ->withClaim('uid', $user->id)
            ->getToken($signer, $key);
        return (string)$tokenObject;
    }


    public function actionLogin()
    {
        $model = new LoginForm();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $postData = Yii::$app->request->post();
        $model->load($postData, '');
        if ($model->validate() && $model->login()) {
            $userModel = $model->getUser();
            $token = $this->generateJwt($userModel);
            return [
                'token' => $token,
                'user' => $userModel,
            ];
        } else {
            return $model->getFirstErrors();
        }
    }


}