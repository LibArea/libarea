<?php

declare(strict_types=1);

namespace App\Bootstrap\Services\Auth;

use App\Models\Auth\AuthModel;
use Html;

class Remember
{
    /**
     * Is there a "remember me" cookie?
     * If there is, then check the `users_auth_tokens` table...
     * Есть cookie «запомнить меня»?
     * Если есть, то проверим по таблице `users_auth_tokens`...
     *
     * @param string $remember
     * @return bool
     */
    public static function check(string $remember): bool|array
    {
        if (empty($remember)) {
            return false;
        }

        // Получим наш селектор | значение валидатора
        [$selector, $validator] = explode(':', $remember);
        $validator = hash('sha256', $validator);

        $token = AuthModel::getAuthTokenBySelector($selector);

        if (empty($token)) {
            return false;
        }

        // The hash does not match
        // Хэш не соответствует
        if (!hash_equals($token['auth_hashedvalidator'], $validator)) {
            return false;
        }

        // Getting data by id
        // Получение данных по id
        $user = AuthModel::getUser($token['auth_user_id'], 'id');

        if (empty($user)) {
            return false;
        }

        // Forced login (disabled for now)
        // Принудительный вход (пока выключен)
        $forceLogin = 0;
        if ($forceLogin > 1) {
            if (rand(1, 100) < $forceLogin) {

                AuthModel::deleteTokenByUserId($token['auth_user_id']);

                return false;
            }
        }

        Action::set($user['id']);

        self::rememberMeReset($token['auth_user_id'], $selector);

        return $user;
    }

    /**
     * Every time a user logs in using their "remember me" cookie
     * Reset the validator and update the database
     * Каждый раз, когда пользователь входит в систему, используя свой файл cookie «запомнить меня»
     * Сбросить валидатор и обновить БД
     *
     * @param integer $user_id
     * @param string $selector
     * @return void
     */
    public static function rememberMeReset(int $user_id, string $selector)
    {
        $existingToken = AuthModel::getAuthTokenBySelector($selector);

        if (empty($existingToken)) {
            self::rememberMe($user_id);
            return true;
        }

        $rememberMeExpire = 30;
        $validator = Html::randomString('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        $token = $selector . ':' . $validator;

        AuthModel::UpdateSelector(
            [
                'auth_hashedvalidator'  => hash('sha256', $validator),
                'auth_expires'          => date('Y-m-d H:i:s', $expires),
                'auth_selector'         => $selector,
            ]
        );

        setcookie("remember", $token, $expires, '/', httponly: true);
    }

    /**
     * Remember me
     * Запомнить меня
     *
     * @param integer $user_id
     * @return void
     */
    public static function rememberMe(int $user_id)
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

        // Let's check if the user already has a set of tokens
        // Проверим, есть ли у пользователя уже набор токенов
        $result = AuthModel::getAuthTokenByUserId($user_id);

        // Recording or updating
        // Записываем или обновляем
        if (empty($result)) {
            AuthModel::insertToken($data);
        } else {
            AuthModel::updateToken($data);
        }

        // Install the token
        // Установим токен
        $token = $selector . ':' . $validator;
        setcookie("remember", $token, $expires, '/', httponly: true);
    }
}
