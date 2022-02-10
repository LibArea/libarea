<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use Validation, Translate, Tpl, UserData;

class LoginController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
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

        if (empty($user['id'])) {
            addMsg('member does not exist', 'error');
            redirect($redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($user['id'])) {
            addMsg('account.being.verified', 'error');
            redirect($redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($user['id'])) {
            addMsg('account.not.activated', 'error');
            redirect($redirect);
        }

        if (!password_verify($password, $user['password'])) {
            addMsg('email.password.not.correct', 'error');
            redirect($redirect);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            (new \App\Controllers\Auth\RememberController())->rememberMe($user['id']);
        }

        (new \App\Controllers\Auth\SessionController())->set($user);

        (new \App\Controllers\AgentController())->set($user['id']);

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

        return Tpl::agRender(
            '/auth/login',
            [
                'meta'  => meta($m, Translate::get('sign.in'), Translate::get('info-login')),
                'data'  => [
                    'sheet' => 'sign.in',
                    'type'  => 'login',
                ]
            ]
        );
    }
}
