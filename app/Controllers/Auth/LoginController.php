<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
use Meta;

use App\Validate\RulesLogin;

class LoginController extends Controller
{
    public function index()
    {
        $data = Request::getPost();

        $user = RulesLogin::rules($data);

        // If you clicked "Remember", it establishes a user session and registers it
        // Если нажал "Запомнить", то устанавливает сеанс пользователя и регистрирует его
        $rememberMe = $data['rememberme'] ?? false;
        if ($rememberMe == 1) {
            (new \App\Controllers\Auth\RememberController())->rememberMe($user['id']);
        }

        (new \App\Controllers\Auth\SessionController())->set($user['id']);

        self::setUserLog($user['id']);

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
            [
                'meta'  => Meta::get(__('app.sign_in'), __('auth.login_info'), $m),
                'data'  => [
                    'sheet' => 'sign.in',
                    'type'  => 'login',
                ]
            ]
        );
    }

    // Let's record the participant's data: browser, platform...
    // Запишем данные участника: браузера, платформы...
    public static function setUserLog($user_id)
    {
        $info = parse_user_agent();
        UserModel::setLogAgent(
            [
                'user_id'       => $user_id,
                'user_browser'  => $info['browser'] . ' ' . $info['version'],
                'user_os'       => $info['platform'],
                'user_ip'       => Request::getRemoteAddress(),
            ]
        );
    }
}
