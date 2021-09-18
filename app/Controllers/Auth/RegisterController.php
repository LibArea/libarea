<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{UserModel, AuthModel};
use Agouti\{Config, Base, Integration, Validation};

class RegisterController extends MainController
{
    // Показ формы регистрации
    public function showRegisterForm()
    {
        // Если включена инвайт система
        if (Config::get(Config::PARAM_INVITE)) {
            redirect('/invite');
        }

        $meta = [
            'h1'            => lang('Sign up'),
            'sheet'         => 'register',
            'canonical'     => Config::get(Config::PARAM_URL) . '/register',
            'meta_title'    => lang('Sign up') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info-security'),
        ];

        return view( '/auth/register', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
    }

    // Отправка запроса для регистрации
    public function index()
    {
        $email      = Request::getPost('email');
        $login      = Request::getPost('login');
        $inv_code   = Request::getPost('invitation_code');
        $inv_uid    = Request::getPostInt('invitation_id');
        $password   = Request::getPost('password');
        $reg_ip     = Request::getRemoteAddress();

        $redirect = $inv_code ? '/register/invite/' . $inv_code : '/register';

        Validation::checkEmail($email, $redirect);

        if (is_array(AuthModel::replayEmail($email))) {
            addMsg(lang('e-mail-replay'), 'error');
            redirect($redirect);
        }

        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            addMsg(lang('multiple-accounts'), 'error');
            redirect($redirect);
        }

        Validation::charset_slug($login, lang('Nickname'), '/register');
        Validation::Limits($login, lang('Nickname'), '3', '10', $redirect);

        if (preg_match('/(\w)\1{3,}/', $login)) {
            addMsg(lang('nickname-repeats-characters'), 'error');
            redirect($redirect);
        }
       
        // Запретим, хотя лучшая практика занять нужные (пр. GitHub)
        $disabled = ['admin', 'support', 'lori', 'loriup', 'dev', 'docs', 'meta', 'email', 'mail', 'login'];
        if (in_array($login, $disabled)) {
            addMsg(lang('nickname-replay'), 'error');
            redirect($redirect);
        }

        if (is_array(AuthModel::replayLogin($login))) {
            addMsg(lang('nickname-replay'), 'error');
            redirect($redirect);
        }

        Validation::Limits($password, lang('Password'), '8', '32', $redirect);
        if (substr_count($password, ' ') > 0) {
            addMsg(lang('password-spaces'), 'error');
            redirect($redirect);
        }

        if (!$inv_code) {
            if (Config::get(Config::PARAM_CAPTCHA)) {
                if (!Integration::checkCaptchaCode()) {
                    addMsg(lang('Code error'), 'error');
                    redirect('/register');
                }
            }
            // Если хакинг формы
            $inv_uid = 0;
        }

        // id участника после регистрации
        $active_uid = UserModel::createUser($login, $email, $password, $reg_ip, $inv_uid);

        if ($inv_uid > 0) {
            // Если регистрация по инвайту, активируем емайл
            UserModel::sendInvitationEmail($inv_code, $inv_uid, $reg_ip, $active_uid);
            addMsg(lang('Successfully, log in'), 'success');
            redirect(getUrlByName('login'));
        }

        // Активация e-mail
        $email_code = Base::randomString('crypto', 20);
        UserModel::sendActivateEmail($active_uid, $email_code);

        // Отправка e-mail
        $link = 'https://' . HLEB_MAIN_DOMAIN . '/email/activate/' . $email_code;
        $mail_message = lang('Activate E-mail') . ": \n" . $link . "\n\n";
        Base::sendMail($email, Config::get(Config::PARAM_NAME) . ' — ' . lang('checking e-mail'), $mail_message);

        addMsg(lang('Check your e-mail to activate your account'), 'success');

        redirect(getUrlByName('login'));
    }

    // Показ формы регистрации с инвайтом
    public function showInviteForm()
    {
        $code   = Request::get('code');
        $invate = UserModel::InvitationAvailable($code);
        
        if (!$invate) {
            addMsg(lang('The code is incorrect'), 'error');
            redirect('/');
        }

        $meta = [
            'sheet'         => 'register',
            'meta_title'    => lang('Registration by invite') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        $data = [
            'invate' => $invate,
        ];

        return view('/auth/register-invate', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
