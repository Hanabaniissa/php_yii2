<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\VarDumper;

class SignupForm extends Model
{

    public string $username;
    public string $password;
    public string $password_repeat;


    public function rules(): array
    {
        return [
            [['username', 'password', 'password_repeat'], 'required'],
            [['username', 'password', 'password_repeat'], 'string', 'min' => 4, 'max' => 16,],
            ['password_repeat', 'compare', 'compareAttribute' => 'password']
        ];
    }


    public function attributeLabels(): array
    {
        {
            return [

                'username' => Yii::t('app', 'UserName'),
                'password' => Yii::t('app', 'Password'),
                'password_repeat' => Yii::t('app', 'PasswordRepeat'),
            ];
        }
    }

    /**
     * @throws Exception
     */
    public function signup(): bool
    {
        $user = new User();
        $user->username = $this->username;
        $user->password = \yii::$app->security->generatePasswordHash($this->password);
        $user->access_token = \yii::$app->security->generateRandomString();
//        $user->access_token = \yii::$app->user->findIdentityByAccessToken();
        $user->auth_key = \yii::$app->security->generateRandomString();
        if ($user->save()) {
            return true;
        }
        \yii::error("User was not saved" . VarDumper::dumpAsString($user->errors));
        return false;


    }

}


?>


