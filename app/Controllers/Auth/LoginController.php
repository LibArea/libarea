<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\AuthModel;
use Lori\Config;
use Lori\Base;

class LoginController extends \MainController
{
    // Отправка запроса авторизации
    public function index()
    {
        $email      = \Request::getPost('email');
        $password   = \Request::getPost('password');
        $rememberMe = \Request::getPostInt('rememberme');

        $url = '/login';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Base::addMsg(lang('Invalid email address'), 'error');
            redirect($url);
        }

        $uid = UserModel::userInfo($email);

        if (empty($uid['user_id'])) {
            Base::addMsg(lang('Member does not exist'), 'error');
            redirect($url);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($uid['user_id'])) {
            Base::addMsg(lang('Your account is under review'), 'error');
            redirect($url);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($uid['user_id'])) {
            Base::addMsg(lang('Your account is not activated'), 'error');
            redirect($url);
        }

        if (!password_verify($password, $uid['user_password'])) {
            Base::addMsg(lang('E-mail or password is not correct'), 'error');
            redirect($url);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            self::rememberMe($uid['user_id']);
        }

        $last_ip = Request::getRemoteAddress();
        UserModel::setUserLastLogs($uid['user_id'], $uid['user_login'], $uid['user_trust_level'], $last_ip);

        Base::setUserSession($uid);

        redirect('/');
    }

    // Страница авторизации
    public function showLoginForm()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Sign in'),
            'sheet'         => 'login',
            'canonical'     => Config::get(Config::PARAM_URL) . '/login',
            'meta_title'    => lang('Sign in') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info_login'),
        ];

        return view(PR_VIEW_DIR . '/auth/login', ['data' => $data, 'uid' => $uid]);
    }

    // ЗАПОМНИТЬ МЕНЯ
    public static function rememberMe($user_id)
    {
        $rememberMeExpire = 30;
        $selector = Base::randomString('crypto', 12);
        $validator = Base::randomString('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установим токен
        $token = $selector . ':' . $validator;

        // Массив данных
        $data = [
            'user_id' => $user_id,
            'selector' => $selector,
            'hashedvalidator' => hash('sha256', $validator),
            'expires' => date('Y-m-d H:i:s', $expires),
        ];

        // ПРОВЕРИМ, ЕСТЬ ЛИ У ИДЕНТИФИКАТОРА ПОЛЬЗОВАТЕЛЯ УЖЕ НАБОР ТОКЕНОВ
        $result = AuthModel::getAuthTokenByUserId($user_id);

        // Записываем
        if (empty($result)) {
            AuthModel::insertToken($data);
        } else {   // Если есть, то обновление
            AuthModel::updateToken($data, $user_id);
        }

        // set_Cookie
        setcookie("remember", $token, $expires);
    }
}
