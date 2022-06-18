<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\User\UserModel;
use App\Models\AuthModel;
use Html;

class RememberController extends MainController
{
    // Есть cookie «запомнить меня»?
    // Если есть, то проверим по таблице `users_auth_tokens`...
    public static function check($remember)
    {
        // Нет
        if (empty($remember)) {
            return;
        }

        // Получим наш селектор | значение валидатора
        [$selector, $validator] = explode(':', $remember);
        $validator = hash('sha256', $validator);

        $token = AuthModel::getAuthTokenBySelector($selector);

        if (empty($token)) {
            return false;
        }

        // Хэш не соответствует
        if (!hash_equals($token['auth_hashedvalidator'], $validator)) {
            return false;
        }

        // Получение данных по id
        $user = UserModel::getUser($token['auth_user_id'], 'id');

        // Нет пользователя
        if (empty($user)) {
            return false;
        }

        // Forced login (disabled for now)
        // Принудительный вход (пока выключен)
        $forceLogin = 0;
        if ($forceLogin > 1) {
            if (rand(1, 100) < $forceLogin) {

                AuthModel::deleteTokenByUserId($token['auth_user_id']);

                return;
            }
        }

        (new \App\Controllers\Auth\SessionController())->set($user['id']);

        self::rememberMeReset($token['auth_user_id'], $selector);

        redirect('/');
    }

    // Каждый раз, когда пользователь входит в систему, используя свой файл cookie «запомнить меня»
    // Сбросить валидатор и обновить БД
    public static function rememberMeReset($user_id, $selector)
    {
        // Получаем по селектору       
        $existingToken = AuthModel::getAuthTokenBySelector($selector);

        if (empty($existingToken)) {
            self::rememberMe($user_id);
            return true;
        }

        $rememberMeExpire = 30;
        $validator = Html::randomString('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установить
        $token = $selector . ':' . $validator;

        AuthModel::UpdateSelector(
            [
                'auth_hashedvalidator'  => hash('sha256', $validator),
                'auth_expires'          => date('Y-m-d H:i:s', $expires),
                'auth_selector'         => $selector,
            ]
        );

        setcookie("remember", $token, $expires, '/');
    }

    // Запомнить меня
    public static function rememberMe($user_id)
    {
        $rememberMeExpire       = 30;
        $selector               = Html::randomString('crypto', 12);
        $validator              = Html::randomString('crypto', 20);
        $expires                = time() + 60 * 60 * 24 * $rememberMeExpire;

        $data = [
            'auth_user_id'          => $user_id,
            'auth_selector'         => $selector,
            'auth_hashedvalidator'  => hash('sha256', $validator),
            'auth_expires'          => date('Y-m-d H:i:s', $expires),
        ];

        // Проверим, есть ли у пользователя уже набор токенов
        $result = AuthModel::getAuthTokenByUserId($user_id);

        // Записываем или обновляем
        if (empty($result)) {
            AuthModel::insertToken($data);
        } else {
            AuthModel::updateToken($data);
        }

        // Установим токен
        $token = $selector . ':' . $validator;
        setcookie("remember", $token, $expires, '/');
    }
}
