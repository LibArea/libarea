<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Agouti\{Config, Base, Validation};


class LoginController extends MainController
{
    // Отправка запроса авторизации
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');

        $redirect = getUrlByName('login');

        Validation::checkEmail($email, $redirect);

        $uid = UserModel::userInfo($email);

        if (empty($uid['user_id'])) {
            addMsg(lang('Member does not exist'), 'error');
            redirect($redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($uid['user_id'])) {
            addMsg(lang('Your account is under review'), 'error');
            redirect($redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($uid['user_id'])) {
            addMsg(lang('Your account is not activated'), 'error');
            redirect($redirect);
        }

        if (!password_verify($password, $uid['user_password'])) {
            addMsg(lang('E-mail or password is not correct'), 'error');
            redirect($redirect);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            Base::rememberMe($uid['user_id']);
        }

        Base::setUserSession($uid);
        $set = (new \App\Controllers\AgentController())->set();

        redirect('/');
    }

    // Страница авторизации
    public function showLoginForm()
    {
        $meta = [
            'sheet'         => 'login',
            'canonical'     => Config::get(Config::PARAM_URL) . getUrlByName('login'),
            'meta_title'    => lang('Sign in') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info-login'),
        ];

        return view('/auth/login', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
    }
}
