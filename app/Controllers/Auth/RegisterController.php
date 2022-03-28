<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{InvitationModel, UserModel};
use App\Models\AuthModel;
use Config, Integration, Validation, SendEmail, Translate, Tpl, Meta, Html, UserData;

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
        if (Config::get('general.invite') == true) {
            redirect('/invite');
        }

        $m = [
            'og'    => false,
            'url'   => getUrlByName('register'),
        ];

        return Tpl::agRender(
            '/auth/register',
            [
                'meta'  => Meta::get($m, Translate::get('registration'), Translate::get('info-security')),
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
            Html::addMsg('email.replay', 'error');
            redirect($redirect);
        }

        # Если домен указанной почты содержится в списке недопустимых
        $arr = explode('@', $email);
        $domain = array_pop($arr);
        if (in_array($domain, Config::get('stop-email'))) {
            redirect($redirect);
        }

        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            Html::addMsg('multiple.accounts', 'error');
            redirect($redirect);
        }

        Validation::Slug($login, Translate::get('nickname'), '/register');
        Validation::Length($login, Translate::get('nickname'), '3', '10', $redirect);

        if (preg_match('/(\w)\1{3,}/', $login)) {
            Html::addMsg('nick.character.repetitions', 'error');
            redirect($redirect);
        }

        // Запретим, хотя лучшая практика занять нужные (пр. GitHub)
        if (in_array($login, Config::get('stop-nickname'))) {
            Html::addMsg('nickname.replay', 'error');
            redirect($redirect);
        }

        if (is_array(AuthModel::checkRepetitions($login, 'login'))) {
            Html::addMsg('nickname-replay', 'error');
            redirect($redirect);
        }

        Validation::Length($password, Translate::get('password'), '8', '32', $redirect);
        if (substr_count($password, ' ') > 0) {
            Html::addMsg('password.spaces', 'error');
            redirect($redirect);
        }

        if ($password != $password_confirm) {
            Html::addMsg('pass.match.err', 'error');
            redirect($redirect);
        }

        if (!$inv_code) {
            if (Config::get('general.captcha')) {
                if (!Integration::checkCaptchaCode()) {
                    Html::addMsg('code.error', 'error');
                    redirect('/register');
                }
            }
            // Если хакинг формы
            $inv_uid = 0;
        }

        $count =  UserModel::getUsersAllCount();
        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        $tl = UserData::USER_FIRST_LEVEL;
        if ($count < 50 && Config::get('general.mode') == true) {
            $tl = UserData::USER_SECOND_LEVEL;
        }

        // id участника после регистрации
        $active_uid = UserModel::create(
            [
                'login'                => $login,
                'email'                => $email,
                'template'             => Config::get('general.template'),
                'lang'                 => Config::get('general.lang'),
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

            Html::addMsg('successfully.login', 'success');

            redirect(getUrlByName('login'));
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
        SendEmail::mailText($active_uid, 'activate.email', ['link' => getUrlByName('activate.code', ['code' => $email_code])]);

        Html::addMsg('check.your.email', 'success');

        redirect(getUrlByName('login'));
    }

    // Показ формы регистрации с инвайтом
    public function showInviteForm()
    {
        $code   = Request::get('code');
        $invate = InvitationModel::available($code);

        if (!$invate) {
            Html::addMsg('code.incorrect', 'error');
            redirect('/');
        }

        return Tpl::agRender(
            '/auth/register-invate',
            [
                'meta'  => Meta::get($m = [], Translate::get('registration.invite')),
                'data'  => [
                    'invate' => $invate,
                    'type'  => 'invite'
                ]
            ]
        );
    }
}
