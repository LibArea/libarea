<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{InvitationModel, UserModel};
use App\Models\AuthModel;
use Google, Validation, SendEmail, Meta, Html, UserData;

class RegisterController extends Controller
{
    // Show registration form
    // Показ формы регистрации
    public function showRegisterForm()
    {
        // If the invite system is enabled
        if (config('general.invite') == true) {
            redirect('/invite');
        }

        $m = [
            'og'    => false,
            'url'   => url('register'),
        ];

        return $this->render(
            '/auth/register',
            'base',
            [
                'meta'  => Meta::get(__('app.registration'), __('app.security_info'), $m),
                'data'  => [
                    'sheet' => 'registration',
                    'type'  => 'register'
                ]
            ]
        );
    }

    public function index()
    {
        $inv_code           = Request::getPost('invitation_code');
        $inv_uid            = Request::getPostInt('invitation_id');
        $password_confirm   = Request::getPost('password_confirm');

        $redirect = $inv_code ? '/register/invite/' . $inv_code : url('register');

        // Check login
        // Проверим login
        $login = Request::getPost('login');
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $login)) {
            is_return(__('msg.slug_correctness'), 'error', $redirect);
        }

        Validation::length($login, 3, 12, 'nickname', $redirect);

        if (preg_match('/(\w)\1{3,}/', $login)) {
            is_return(__('msg.nick_character'), 'error', $redirect);
        }

        if (in_array($login, config('stop-nickname'))) {
            is_return(__('msg.nick_exist'), 'error', $redirect);
        }

        if (is_array(AuthModel::checkRepetitions($login, 'login'))) {
            is_return(__('msg.nick_exist'), 'error', $redirect);
        }

        // Check Email
        // Проверим Email
        Validation::email($email = Request::getPost('email'), $redirect);

        if (is_array(AuthModel::checkRepetitions($email, 'email'))) {
            is_return(__('msg.email_replay'), 'error', $redirect);
        }

        $arr = explode('@', $email);
        $domain = array_pop($arr);
        if (in_array($domain, config('stop-email'))) {
            is_return(__('msg.email_replay'), 'error', $redirect);
        }

        // Check ip for ban
        // Запрет Ip на бан
        $reg_ip = Request::getRemoteAddress();
        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            is_return(__('msg.multiple_accounts'), 'error', $redirect);
        }

        // Let's check the password
        // Проверим пароль
        $password = Request::getPost('password');
        Validation::length($password, 8, 32, 'password', $redirect);

        if (substr_count($password, ' ') > 0) {
            is_return(__('msg.password_spaces'), 'error', $redirect);
        }

        if ($password != $password_confirm) {
            is_return(__('msg.pass_match_err'), 'error', $redirect);
        }

        // Let's check the verification code
        // Проверим код проверки
        if (!$inv_code) {
            if (config('integration.captcha')) {
                if (!Google::checkCaptchaCode()) {
                    is_return(__('msg.code_error'), 'error', $redirect);
                }
            }
            // Если хакинг формы (If form hacking)
            $inv_uid = 0;
        }

        // For "launch mode", the first 50 members get trust_level = 2
        // Для "режима запуска" первые 50 участников получают trust_level = 2 
        $tl = UserData::USER_FIRST_LEVEL;
        if (UserModel::getUsersAllCount() < 50 && config('general.mode') == true) {
            $tl = UserData::USER_SECOND_LEVEL;
        }

        $active_uid = UserModel::create(
            [
                'login'                => $login,
                'email'                => $email,
                'template'             => config('general.template'),
                'lang'                 => config('general.lang'),
                'whisper'              => '',
                'password'             => password_hash($password, PASSWORD_BCRYPT),
                'limiting_mode'        => 0, // режим заморозки выключен
                'activated'            => $inv_uid > 0 ? 1 : 0, // если инвайта нет, то активация
                'reg_ip'               => $reg_ip,
                'trust_level'          => $tl,
                'invitation_id'        => $inv_uid,
            ]
        );

        if ($inv_uid > 0) {
            // If registration by invite, activate the email
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

            is_return(__('msg.change_saved'), 'success', url('login'));
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

        is_return(__('msg.check_your_email'), 'success', url('login'));
    }

    // Show registration form with invite
    // Показ формы регистрации с инвайтом
    public function showInviteForm()
    {
        $code   = Request::get('code');
        $invate = InvitationModel::available($code);

        if (!$invate) {
            is_return(__('msg.code_incorrect'), 'error', '/');
        }

        return $this->render(
            '/auth/register-invate',
            'base',
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
