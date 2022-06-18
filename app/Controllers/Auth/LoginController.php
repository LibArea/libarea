<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
use Meta, Validation;

class LoginController extends Controller
{
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');
        $redirect   = url('login');

        Validation::email($email = Request::getPost('email'), $redirect);

        $user = UserModel::userInfo($email);

        if (empty($user['id'])) {
            Validation::comingBack(__('msg.no_user'), 'error', $redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($user['id'])) {
            Validation::comingBack(__('msg.account_verified'), 'error', $redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($user['id'])) {
            Validation::comingBack(__('msg.not_activated'), 'error', $redirect);
        }

        if (!password_verify($password, $user['password'])) {
            Validation::comingBack(__('msg.not_correct'), 'error', $redirect);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            (new \App\Controllers\Auth\RememberController())->rememberMe($user['id']);
        }

        (new \App\Controllers\Auth\SessionController())->set($user['id']);

        (new \App\Controllers\AgentController())->set($user['id']);

        redirect('/');
    }

    // Login page
    // Страница авторизации
    public function showLoginForm()
    {
        $m = [
            'og'    => false,
            'url'   => url('login'),
        ];

        return $this->render(
            '/auth/login',
            'base',
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
