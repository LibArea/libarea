<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\UserModel;
use Validation, Translate;

class LoginController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = UserData::getUid();
    }

    // Отправка запроса авторизации
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');

        $redirect   = getUrlByName('login');

        Validation::Email($email, $redirect);

        $user = UserModel::userInfo($email);

        if (empty($user['user_id'])) {
            addMsg(Translate::get('member does not exist'), 'error');
            redirect($redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($user['user_id'])) {
            addMsg(Translate::get('your account is under review'), 'error');
            redirect($redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($user['user_id'])) {
            addMsg(Translate::get('your account is not activated'), 'error');
            redirect($redirect);
        }

        if (!password_verify($password, $user['user_password'])) {
            addMsg(Translate::get('email.password.not.correct'), 'error');
            redirect($redirect);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            (new \App\Controllers\Auth\RememberController())->rememberMe($user['user_id']);
        }

        (new \App\Controllers\Auth\SessionController())->set($user);

        (new \App\Controllers\AgentController())->set();

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

        return agRender(
            '/auth/login',
            [
                'meta'  => meta($m, Translate::get('sign in'), Translate::get('info-login')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet' => 'sign in',
                    'type'  => 'login',
                ]
            ]
        );
    }
}
