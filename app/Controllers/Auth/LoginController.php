<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Bootstrap\Services\Auth\{Login, Action, Remember};
use App\Validate\RulesLogin;
use Meta;

class LoginController extends Controller
{
    /**
     * Authorization
     * Авторизация
     *
     * @return void
     */
    public function index(): void
    {
        $data = Request::input();
        $user = RulesLogin::rules($data);

        // If you clicked "Remember", it establishes a user session and registers it
        // Если нажал "Запомнить", то устанавливает сеанс пользователя и регистрирует его
        $rememberMe = $data['rememberme'] ?? false;
        if ($rememberMe == 1) {
            Remember::rememberMe($user['id']);
        }

        Action::set($user['id']);

        Login::setUserLog($user['id']);

        redirect('/');
    }

    /**
     * Login page
     * Страница авторизации
     */
    public function showLoginForm(): void
    {
        $m = [
            'og'    => false,
            'url'   => url('login'),
        ];

        render(
            '/auth/login',
            [
                'meta'  => Meta::get(__('app.sign_in'), __('auth.login_info'), $m),
                'data'  => [
                    'sheet' => 'sign.in',
                    'type'  => 'login',
                ]
            ]
        );
    }
}
