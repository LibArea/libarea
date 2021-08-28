<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Lori\{Config, Base, Validation};


class LoginController extends MainController
{
    // Отправка запроса авторизации
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');

        $redirect = '/login';

        Validation::checkEmail($email, $redirect);

        $uid = UserModel::userInfo($email);

        if (empty($uid['user_id'])) {
            Base::addMsg(lang('Member does not exist'), 'error');
            redirect($redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($uid['user_id'])) {
            Base::addMsg(lang('Your account is under review'), 'error');
            redirect($redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($uid['user_id'])) {
            Base::addMsg(lang('Your account is not activated'), 'error');
            redirect($redirect);
        }

        if (!password_verify($password, $uid['user_password'])) {
            Base::addMsg(lang('E-mail or password is not correct'), 'error');
            redirect($redirect);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            Base::rememberMe($uid['user_id']);
        }

        $last_ip = Request::getRemoteAddress();
        UserModel::setUserLastLogs($uid['user_id'], $uid['user_login'], $uid['user_trust_level'], $last_ip);

        Base::setUserSession($uid);

        redirect('/');
    }

    // Страница авторизации
    public function showLoginForm()
    {
        $meta = [
            'sheet'         => 'login',
            'canonical'     => Config::get(Config::PARAM_URL) . '/login',
            'meta_title'    => lang('Sign in') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info_login'),
        ];

        return view('/auth/login', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
    }
}
