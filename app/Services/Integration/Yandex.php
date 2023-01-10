<?php

namespace App\Services\Integration;

use Hleb\Constructor\Handlers\Request;
use Curl;

class Yandex
{
    public static function callApi($params)
    {
        $code = Request::getPost('code');

        if (!empty($code)) {

            $sending_url = 'https://oauth.yandex.ru/token'; // отправка
            $receiving_url = 'https://login.yandex.ru/info'; // получение

            $params = [
                'grant_type'    => 'authorization_code',
                'code'          => $code,
                'client_id'     => 'ID_приложения',
                'client_secret' => 'ПАРОЛЬ_приложения',
            ];

            $info = Curl::index($sending_url, $params, ['Accept: application/json']);
            $data = json_decode($info, true);

            if (!empty($data['access_token'])) {

                $info = Curl::index($receiving_url, ['format' => 'json'], ['Authorization: OAuth ' . $data['access_token']]);
                $data = json_decode($info, true);
            }
        }

        return $data;
    }
}
