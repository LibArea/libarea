<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Hleb\Base\Controller;
use App\Models\User\UserModel;
use App\Models\Auth\AuthModel;
use App\Bootstrap\Services\Auth\Action;
use Html;

class RememberController extends Controller
{
    /**
     * Есть cookie «запомнить меня»?
     * Если есть, то проверим по таблице `users_auth_tokens`...
     *
     * @param string $remember
     * @return boolean
     */
    public static function check(string $remember): bool
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

        // Хэш не соответствует
        if (!hash_equals($token['auth_hashedvalidator'], $validator)) {
            return false;
        }

        // Получение данных по id
        $user = UserModel::get($token['auth_user_id'], 'id');

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

                return true;
            }
        }

        Action::set($user['id']);

        self::rememberMeReset($token['auth_user_id'], $selector);

        return true;
    }

    /**
     * Каждый раз, когда пользователь входит в систему, используя свой файл cookie «запомнить меня»
     * Сбросить валидатор и обновить БД
     *
     * @param integer $user_id
     * @param mixed $selector
     * @return void
     */
    public static function rememberMeReset(int $user_id, mixed $selector): void
    {
        // Получаем по селектору       
        $existingToken = AuthModel::getAuthTokenBySelector($selector);

        if (empty($existingToken)) {
            self::rememberMe($user_id);
            return;
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

        setcookie("remember", $token, $expires, '/', httponly: true);
    }

    // Запомнить меня
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
        setcookie("remember", $token, $expires, '/', httponly: true);
    }
}
