<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use Base, Validation, Translate;


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
            addMsg(Translate::get('member does not exist'), 'error');
            redirect($redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($uid['user_id'])) {
            addMsg(Translate::get('your account is under review'), 'error');
            redirect($redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($uid['user_id'])) {
            addMsg(Translate::get('your account is not activated'), 'error');
            redirect($redirect);
        }

        if (!password_verify($password, $uid['user_password'])) {
            addMsg(Translate::get('e-mail or password is not correct'), 'error');
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
        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('login'),
        ];
        $meta = meta($m, Translate::get('sign up'), Translate::get('info-login'));

        $data = [
            'sheet'         => 'sign up',
        ];

        return view('/auth/login', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
