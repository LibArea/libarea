<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
use Validation, Meta;

class LoginController extends Controller
{
    // Отправка запроса авторизации
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');

        $redirect   = url('login');

        Validation::Email($email, $redirect);

        $user = UserModel::userInfo($email);

        if (empty($user['id'])) {
            Validation::ComeBack('msg.no_user', 'error', $redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($user['id'])) {
            Validation::ComeBack('msg.account_verified', 'error', $redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($user['id'])) {
            Validation::ComeBack('msg.not_activated', 'error', $redirect);
        }

        if (!password_verify($password, $user['password'])) {
            Validation::ComeBack('msg.not_correct', 'error', $redirect);
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
            'og'    => false,
            'url'   => url('login'),
        ];

        return $this->render(
            '/auth/login',
            [
                'meta'  => Meta::get(__('app.sign_in'), __('app.login_info'), $m),
                'data'  => [
                    'sheet' => 'sign.in',
                    'type'  => 'login',
                ]
            ]
        );
    }
}
