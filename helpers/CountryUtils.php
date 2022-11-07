<?php

namespace app\helpers;
use Yii;
use yii\web\Cookie;

class CountryUtils
{
    public static function setPreferredCountry($countryId)
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

    public static function getPreferredCountry()
    {
        $currentCookies = Yii::$app->request->cookies;
        if (!empty($currentCookies['country']->value)) return $currentCookies['country']->value;
        return 0;
    }
}