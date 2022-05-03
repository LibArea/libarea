<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{InvitationModel, UserModel};
use App\Models\AuthModel;
use Integration, Validation, SendEmail, Tpl, Meta, Html, UserData;

class RegisterController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    // Показ формы регистрации
    public function showRegisterForm()
    {
        // Если включена инвайт система
        if (config('general.invite') == true) {
            redirect('/invite');
        }

        $m = [
            'og'    => false,
            'url'   => url('register'),
        ];

        return Tpl::LaRender(
            '/auth/register',
            [
                'meta'  => Meta::get(__('app.registration'), __('app.security_info'), $m),
                'data'  => [
                    'sheet' => 'registration',
                    'type'  => 'register'
                ]
            ]
        );
    }

    // Отправка запроса для регистрации
    public function index()
    {
        $email              = Request::getPost('email');
        $login              = Request::getPost('login');
        $inv_code           = Request::getPost('invitation_code');
        $inv_uid            = Request::getPostInt('invitation_id');
        $password           = Request::getPost('password');
        $password_confirm   = Request::getPost('password_confirm');
        $reg_ip             = Request::getRemoteAddress();

        $redirect = $inv_code ? '/register/invite/' . $inv_code : '/register';

        Validation::Email($email, $redirect);

        if (is_array(AuthModel::checkRepetitions($email, 'email'))) {
            Validation::ComeBack('msg.email_replay', 'error', $redirect);
        }

        # Если домен указанной почты содержится в списке недопустимых
        $arr = explode('@', $email);
        $domain = array_pop($arr);
        if (in_array($domain, config('stop-email'))) {
            redirect($redirect);
        }

        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            Validation::ComeBack('msg.multiple_accounts', 'error', $redirect);
        }

        Validation::Slug($login, 'nickname', '/register');
        Validation::Length($login, 'nickname', '3', '10', $redirect);

        if (preg_match('/(\w)\1{3,}/', $login)) {
            Validation::ComeBack('msg.nick_character', 'error', $redirect);
        }

        // Запретим, хотя лучшая практика занять нужные (пр. GitHub)
        if (in_array($login, config('stop-nickname'))) {
            Validation::ComeBack('msg.nickname_replay', 'error', $redirect);
        }

        if (is_array(AuthModel::checkRepetitions($login, 'login'))) {
            Validation::ComeBack('msg.nickname_replay', 'error', $redirect);
        }

        Validation::Length($password, 'password', '8', '32', $redirect);
        if (substr_count($password, ' ') > 0) {
            Validation::ComeBack('msg.password_spaces', 'error', $redirect);
        }

        if ($password != $password_confirm) {
            Validation::ComeBack('msg.pass_match_err', 'error', $redirect);
        }

        if (!$inv_code) {
            if (config('general.captcha')) {
                if (!Integration::checkCaptchaCode()) {
                    Validation::ComeBack('msg.code_error', 'error', '/register');
                }
            }
            // Если хакинг формы
            $inv_uid = 0;
        }

        $count =  UserModel::getUsersAllCount();
        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        $tl = UserData::USER_FIRST_LEVEL;
        if ($count < 50 && config('general.mode') == true) {
            $tl = UserData::USER_SECOND_LEVEL;
        }

        // id участника после регистрации
        $active_uid = UserModel::create(
            [
                'login'                => $login,
                'email'                => $email,
                'template'             => config('general.template'),
                'lang'                 => config('general.lang'),
                'whisper'              => '',
                'password'             => password_hash($password, PASSWORD_BCRYPT),
                'limiting_mode'        => 0, // Режим заморозки выключен
                'activated'            => $inv_uid > 0 ? 1 : 0, // если инвайта нет, то активация
                'reg_ip'               => $reg_ip,
                'trust_level'          => $tl,
                'invitation_id'        => $inv_uid,
            ]
        );

        if ($inv_uid > 0) {
            // Если регистрация по инвайту, активируем емайл
            InvitationModel::activate(
                [
                    'uid'               => $inv_uid,
                    'active_status'     => 1,
                    'active_ip'         => $reg_ip,
                    'active_time'       => date('Y-m-d H:i:s'),
                    'active_uid'        => $active_uid,
                    'invitation_code'   => $inv_code,
                ]
            );

            Validation::ComeBack('msg.successfully_login', 'success', url('login'));
        }

        // Email Activation
        $email_code = Html::randomString('crypto', 20);
        UserModel::sendActivateEmail(
            [
                'user_id'       => $active_uid,
                'email_code'    => $email_code,
            ]
        );

        // Sending email
        SendEmail::mailText($active_uid, 'activate.email', ['link' => url('activate.code', ['code' => $email_code])]);

        Validation::ComeBack('msg.check_your_email', 'success', url('login'));
    }

    // Показ формы регистрации с инвайтом
    public function showInviteForm()
    {
        $code   = Request::get('code');
        $invate = InvitationModel::available($code);

        if (!$invate) {
            Validation::ComeBack('msg.code_incorrect', 'error', '/');
        }

        return Tpl::LaRender(
            '/auth/register-invate',
            [
                'meta'  => Meta::get(__('app.reg_invite')),
                'data'  => [
                    'invate' => $invate,
                    'type'  => 'invite'
                ]
            ]
        );
    }
}
