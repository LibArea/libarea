<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Lori\{Config, Base, Integration};

class RecoverController extends MainController
{
    public function showPasswordForm()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Password Recovery'),
            'sheet'         => 'login',
            'meta_title'    => lang('Password Recovery') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/auth/recover', ['data' => $data, 'uid' => $uid]);
    }

    public function index()
    {
        $email = Request::getPost('email');

        if (Config::get(Config::PARAM_CAPTCHA)) {
            if (!Integration::checkCaptchaCode()) {
                Base::addMsg(lang('Code error'), 'error');
                redirect('/recover');
            }
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Base::addMsg(lang('Invalid') . ' email', 'error');
            redirect('/recover');
        }

        $uInfo = UserModel::userInfo($email);

        if (empty($uInfo['user_email'])) {
            Base::addMsg(lang('There is no such e-mail on the site'), 'error');
            redirect('/recover');
        }

        // Проверка на заблокированный аккаунт
        if ($uInfo['user_ban_list'] == 1) {
            Base::addMsg(lang('Your account is under review'), 'error');
            redirect('/recover');
        }

        $code = $uInfo['user_id'] . '-' . Base::randomString('crypto', 25);
        UserModel::initRecover($uInfo['user_id'], $code);

        // Добавим текс письма тут
        $newpass_link = 'https://' . HLEB_MAIN_DOMAIN . '/recover/remind/' . $code;
        $mail_message = "Your link to change your password: \n" . $newpass_link . "\n\n";

        Base::mailText($email, Config::get(Config::PARAM_NAME) . ' - changing your password', $mail_message);

        Base::addMsg(lang('New password has been sent to e-mail'), 'success');
        redirect('/login');
    }

    // Страница установки нового пароля
    public function showRemindForm()
    {
        // Код активации
        $code = Request::get('code');

        // Проверяем код
        $user_id = UserModel::getPasswordActivate($code);
        if (!$user_id) {
            Base::addMsg(lang('code-incorrect'), 'error');
            redirect('/recover');
        }

        $user = UserModel::getUser($user_id['activate_user_id'], 'id');
        Base::PageError404($user);

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Password Recovery'),
            'code'          => $code,
            'user_id'       => $user_id['activate_user_id'],
            'sheet'         => 'recovery',
            'meta_title'    => lang('Password Recovery') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/auth/newrecover', ['data' => $data, 'uid' => $uid]);
    }

    public function remindNew()
    {
        $password   = Request::getPost('password');
        $code       = Request::getPost('code');
        $user_id    = Request::getPost('user_id');

        if (!$user_id) {
            return false;
        }

        Base::Limits($password, lang('Password'), '8', '32', '/recover/remind/' . $code);

        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        $news     = UserModel::editPassword($user_id, $newpass);

        if (!$news) {
            return false;
        }

        UserModel::editRecoverFlag($user_id);

        Base::addMsg(lang('Password changed'), 'success');
        redirect('/login');
    }

    // Проверка корректности E-mail
    public function AvtivateEmail()
    {
        // Код активации
        $code = Request::get('code');

        // Проверяем код
        $avtivate_email = UserModel::getEmailActivate($code);
        if (!$avtivate_email) {
            Base::addMsg(lang('code-used'), 'error');
            redirect('/');
        }

        UserModel::EmailActivate($avtivate_email['user_id']);

        Base::addMsg(lang('yes-email-pass'), 'success');
        redirect('/login');
    }
}
