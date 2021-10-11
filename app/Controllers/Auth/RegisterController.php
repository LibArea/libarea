<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{InvitationModel, UserModel};
use App\Models\AuthModel;
use Config, Base, Integration, Validation, SendEmail;

class RegisterController extends MainController
{
    // Показ формы регистрации
    public function showRegisterForm()
    {
        // Если включена инвайт система
        if (Config::get('general.invite') == 1) {
            redirect('/invite');
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('register'),
        ];
        $meta = meta($m, lang('sign up'), lang('info-security'));

        $data = [
            'sheet'         => 'sign up',
        ];

        return view('/auth/register', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
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

        # Если домен указанной почты содержится в списке недопустимых
        $domain = array_pop(explode('@', $email));
        if (in_array($domain, Config::arr('stop-email'))) {
            redirect($redirect);
        }

        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            addMsg(lang('multiple-accounts'), 'error');
            redirect($redirect);
        }

        Validation::charset_slug($login, lang('nickname'), '/register');
        Validation::Limits($login, lang('nickname'), '3', '10', $redirect);

        if (preg_match('/(\w)\1{3,}/', $login)) {
            addMsg(lang('nickname-repeats-characters'), 'error');
            redirect($redirect);
        }

        // Запретим, хотя лучшая практика занять нужные (пр. GitHub)
        if (in_array($login, Config::arr('stop-nickname'))) {
            addMsg(lang('nickname-replay'), 'error');
            redirect($redirect);
        }

        if (is_array(AuthModel::replayLogin($login))) {
            addMsg(lang('nickname-replay'), 'error');
            redirect($redirect);
        }

        Validation::Limits($password, lang('password'), '8', '32', $redirect);
        if (substr_count($password, ' ') > 0) {
            addMsg(lang('password-spaces'), 'error');
            redirect($redirect);
        }

        if (!$inv_code) {
            if (Config::get('general.captcha')) {
                if (!Integration::checkCaptchaCode()) {
                    addMsg(lang('code error'), 'error');
                    redirect('/register');
                }
            }
            // Если хакинг формы
            $inv_uid = 0;
        }

        $count =  UserModel::getUsersAllCount('all');
        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        $tl = 0;
        if ($count < 50 && Config::get('general.mode') == 1) {
            $tl = 1;
        }


        // id участника после регистрации
        $active_uid = UserModel::createUser($login, $email, $password, $reg_ip, $inv_uid, $tl);

        if ($inv_uid > 0) {
            // Если регистрация по инвайту, активируем емайл
            InvitationModel::activate($inv_code, $inv_uid, $reg_ip, $active_uid);
            addMsg(lang('successfully, log in'), 'success');
            redirect(getUrlByName('login'));
        }

        // Активация e-mail
        $email_code = Base::randomString('crypto', 20);
        UserModel::sendActivateEmail($active_uid, $email_code);

        // Отправка e-mail
        $link = Config::get('meta.url') . '/email/activate/' . $email_code;
        $mail_message = lang('activate e-mail') . ": \n" . $link . "\n\n";
        SendEmail::send($email, Config::get('meta.name') . ' — ' . lang('checking e-mail'), $mail_message);

        addMsg(lang('check your e-mail to activate your account'), 'success');

        redirect(getUrlByName('login'));
    }

    // Показ формы регистрации с инвайтом
    public function showInviteForm()
    {
        $code   = Request::get('code');
        $invate = InvitationModel::available($code);

        if (!$invate) {
            addMsg(lang('the code is incorrect'), 'error');
            redirect('/');
        }

        $meta = meta($m = [], lang('registration by invite'));
        $data = [
            'invate' => $invate,
        ];

        return view('/auth/register-invate', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
