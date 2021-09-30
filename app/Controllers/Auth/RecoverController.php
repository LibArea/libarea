<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Agouti\{Config, Base, Integration, Validation};

class RecoverController extends MainController
{
    public function showPasswordForm()
    {
        $meta = [
            'sheet'         => 'recover',
            'meta_title'    => lang('password Recovery') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        $data = [
            'sheet'         => 'recover',
        ];

        return view('/auth/recover', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    public function index()
    {
        $email          = Request::getPost('email');
        $recover_uri    = getUrlByName('recover');
        
        if (Config::get(Config::PARAM_CAPTCHA)) {
            if (!Integration::checkCaptchaCode()) {
                addMsg(lang('code error'), 'error');
                redirect($recover_uri);
            }
        }

        Validation::checkEmail($email, $recover_uri);

        $uInfo = UserModel::userInfo($email);

        if (empty($uInfo['user_email'])) {
            addMsg(lang('there is no such e-mail on the site'), 'error');
            redirect($recover_uri);
        }

        // Проверка на заблокированный аккаунт
        if ($uInfo['user_ban_list'] == 1) {
            addMsg(lang('your account is under review'), 'error');
            redirect($recover_uri);
        }

        $code = $uInfo['user_id'] . '-' . Base::randomString('crypto', 25);
        UserModel::initRecover($uInfo['user_id'], $code);

        // Отправка e-mail
        $newpass_link = 'https://' . HLEB_MAIN_DOMAIN . $recover_uri . '/remind/' . $code;
        $mail_message = lang('your link to change your password'). ": \n" . $newpass_link . "\n\n";
        Base::sendMail($email, Config::get(Config::PARAM_NAME) . ' — ' . lang('changing your password'), $mail_message);

        addMsg(lang('new password has been sent to e-mail'), 'success');
        redirect(getUrlByName('login'));
    }

    // Страница установки нового пароля
    public function showRemindForm()
    {
        $code       = Request::get('code');
        $user_id    = UserModel::getPasswordActivate($code);
        
        if (!$user_id) {
            addMsg(lang('code-incorrect'), 'error');
            redirect(getUrlByName('recover'));
        }

        $user = UserModel::getUser($user_id['activate_user_id'], 'id');
        Base::PageError404($user);

        $meta = [
            'sheet'         => 'recovery',
            'meta_title'    => lang('password Recovery') . ' | ' . Config::get(Config::PARAM_NAME),
        ];
        
        $data = [
            'code'          => $code,
            'user_id'       => $user_id['activate_user_id'],
            'sheet'         => 'recovery',
        ];

        return view('/auth/newrecover', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    public function remindNew()
    {
        $password   = Request::getPost('password');
        $code       = Request::getPost('code');
        $user_id    = Request::getPost('user_id');

        if (!$user_id) {
            return false;
        }

        Validation::Limits($password, lang('password'), '8', '32', getUrlByName('recover') . '/remind/' . $code);

        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        $news     = UserModel::editPassword($user_id, $newpass);

        if (!$news) {
            return false;
        }

        UserModel::editRecoverFlag($user_id);

        addMsg(lang('password changed'), 'success');
        redirect(getUrlByName('login'));
    }

    // Проверка корректности E-mail
    public function ActivateEmail()
    {
        $code = Request::get('code');
        $activate_email = UserModel::getEmailActivate($code);
        
        if (!$activate_email) {
            addMsg(lang('code-used'), 'error');
            redirect('/');
        }

        UserModel::EmailActivate($activate_email['user_id']);

        addMsg(lang('yes-email-pass'), 'success');
        redirect(getUrlByName('login'));
    }
}
