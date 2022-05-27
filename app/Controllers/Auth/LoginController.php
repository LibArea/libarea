<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
use Meta;

class LoginController extends Controller
{
    // Отправка запроса авторизации
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(['error' => 'error', 'text' => __('msg.email_correctness')]);
        }

        $user = UserModel::userInfo($email);

        if (empty($user['id'])) {
            return json_encode(['error' => 'error', 'text' => __('msg.no_user')]);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($user['id'])) {
            return json_encode(['error' => 'error', 'text' => __('msg.account_verified')]);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($user['id'])) {
            return json_encode(['error' => 'error', 'text' => __('msg.not_activated')]);
        }

        if (!password_verify($password, $user['password'])) {
            return json_encode(['error' => 'error', 'text' => __('msg.not_correct')]);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            (new \App\Controllers\Auth\RememberController())->rememberMe($user['id']);
        }

        (new \App\Controllers\Auth\SessionController())->set($user);

        (new \App\Controllers\AgentController())->set($user['id']);

        return true;
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
