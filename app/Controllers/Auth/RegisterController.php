<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{InvitationModel, UserModel};
use App\Models\AuthModel;
use Integration, Validation, SendEmail, Meta, Html, UserData;

class RegisterController extends Controller
{
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

        return $this->render(
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
        $inv_code           = Request::getPost('invitation_code');
        $inv_uid            = Request::getPostInt('invitation_id');
        $password_confirm   = Request::getPost('password_confirm');

        $redirect = $inv_code ? '/register/invite/' . $inv_code : '/register';

        // Проверим login
        $login = Request::getPost('login');
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $login)) {
            return json_encode(['error' => 'error', 'text' => __('msg.slug_correctness', ['name' => '«' . __('msg.nickname') . '»'])]);
        }
        
        if (!Validation::length($login, 3, 12)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.nickname') . '»'])]);
        }
        
        if (preg_match('/(\w)\1{3,}/', $login)) {
            return json_encode(['error' => 'error', 'text' => __('msg.nick_character')]);
        }

        if (in_array($login, config('stop-nickname'))) {
            return json_encode(['error' => 'error', 'text' => __('msg.nick_exist')]);
        }

        if (is_array(AuthModel::checkRepetitions($login, 'login'))) {
            return json_encode(['error' => 'error', 'text' => __('msg.nick_exist')]);
        }

        // Check Email
        // Проверим Email
        if (!filter_var($email  = Request::getPost('email'), FILTER_VALIDATE_EMAIL)) {
            return json_encode(['error' => 'error', 'text' => __('msg.email_correctness')]);
        }

        if (is_array(AuthModel::checkRepetitions($email, 'email'))) {
            return json_encode(['error' => 'error', 'text' => __('msg.email_replay')]);
        }

        $arr = explode('@', $email);
        $domain = array_pop($arr);
        if (in_array($domain, config('stop-email'))) {
            return json_encode(['error' => 'error', 'text' => __('msg.email_replay')]);
        }

        // Check ip for ban
        // Запрет Ip на бан
        $reg_ip = Request::getRemoteAddress();
        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            return json_encode(['error' => 'error', 'text' => __('msg.multiple_accounts')]);
        }

        // Let's check the password
        // Проверим пароль
        $password = Request::getPost('password');
        if (!Validation::length($password, 8, 32)) {
            $msg = __('msg.string_length', ['name' => '«' . __('msg.password') . '»']);
            return json_encode(['error' => 'error', 'text' => $msg]);
        }
        
        if (substr_count($password, ' ') > 0) {
            return json_encode(['error' => 'error', 'text' => __('msg.password_spaces')]);
        }

        if ($password != $password_confirm) {
            return json_encode(['error' => 'error', 'text' => __('msg.pass_match_err')]);
        }

        // Let's check the verification code
        // Проверим код проверки
        if (!$inv_code) {
            if (config('general.captcha')) {
                if (!Integration::checkCaptchaCode()) {
                    return json_encode(['error' => 'error', 'text' => __('msg.code_error')]);
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

            return true;
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

        return true;
    }

    // Show registration form with invite
    // Показ формы регистрации с инвайтом
    public function showInviteForm()
    {
        $code   = Request::get('code');
        $invate = InvitationModel::available($code);

        if (!$invate) {
            Validation::ComeBack('msg.code_incorrect', 'error', '/');
        }

        return $this->render(
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
