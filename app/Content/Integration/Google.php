<?php

declare(strict_types=1);

namespace App\Content\Integration;

use Hleb\Static\Request;
use Curl;

class Google
{
    // Captcha v2
    private static function callApi($params)
    {
        $api_url = 'https://www.google.com/recaptcha/api/siteverify';

        if (!function_exists('curl_init')) {
            $data = @file_get_contents($api_url . '?' . http_build_query($params));
        } else {
            $data =  Curl::index($api_url, $params, ['Accept: application/json']);
        }

        if (!$data) return false;

        $data = json_decode($data, true);

        return $data['success'] ?? false;
    }

    // Проверка в AuthControllerе
    public static function checkCaptchaCode()
    {
        $response = Request::post('g-recaptcha-response')->value();

        if (!$response) return false;

        return self::callApi(array(
            'secret'   => config('integration', 'captcha_private_key'),
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ));
    }
}
