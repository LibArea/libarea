<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{SettingModel, UserModel};
use Config, Base, Integration, Validation, SendEmail, Translate;

class RecoverController extends MainController
{
    public function showPasswordForm()
    {
        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('recover'),
        ];

        return agRender(
            '/auth/recover',
            [
                'meta'  => meta($m, Translate::get('password recovery'), Translate::get('info-recover')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'sheet' => 'recover',
                    'type'  => 'recover',
                ]
            ]
        );
    }

    public function index()
    {
        $email          = Request::getPost('email');
        $recover_uri    = getUrlByName('recover');

        if (Config::get('general.captcha')) {
            if (!Integration::checkCaptchaCode()) {
                addMsg(Translate::get('code error'), 'error');
                redirect($recover_uri);
            }
        }

        Validation::checkEmail($email, $recover_uri);

        $uInfo = UserModel::userInfo($email);

        if (empty($uInfo['user_email'])) {
            addMsg(Translate::get('there is no such e-mail on the site'), 'error');
            redirect($recover_uri);
        }

        // Проверка на заблокированный аккаунт
        if ($uInfo['user_ban_list'] == 1) {
            addMsg(Translate::get('your account is under review'), 'error');
            redirect($recover_uri);
        }

        $code = $uInfo['user_id'] . '-' . randomString('crypto', 25);
        UserModel::initRecover($uInfo['user_id'], $code);

        // Отправка e-mail
        $newpass_link = Config::get('meta.url') . $recover_uri . '/remind/' . $code;
        $mail_message = Translate::get('linkchange password') . ": \n" . $newpass_link . "\n\n";
        SendEmail::send($email, Config::get('meta.name') . ' — ' . Translate::get('changing password'), $mail_message);

        addMsg(Translate::get('new password email'), 'success');
        redirect(getUrlByName('login'));
    }

    // Страница установки нового пароля
    public function showRemindForm()
    {
        $code       = Request::get('code');
        $user_id    = UserModel::getPasswordActivate($code);

        if (!$user_id) {
            addMsg(Translate::get('code-incorrect'), 'error');
            redirect(getUrlByName('recover'));
        }

        $user = UserModel::getUser($user_id['activate_user_id'], 'id');
        pageError404($user);

        return agRender(
            '/auth/newrecover',
            [
                'meta'  => meta($m = [], Translate::get('password recovery'), Translate::get('info-recover')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'code'      => $code,
                    'user_id'   => $user_id['activate_user_id'],
                    'sheet'     => 'recovery',
                    'type'      => 'newrecover',
                ]
            ]
        );
    }

    public function remindNew()
    {
        $password   = Request::getPost('password');
        $code       = Request::getPost('code');
        $user_id    = Request::getPost('user_id');

        if (!$user_id) {
            return false;
        }

        Validation::Limits($password, Translate::get('password'), '8', '32', getUrlByName('recover') . '/remind/' . $code);

        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        $news     = SettingModel::editPassword($user_id, $newpass);

        if (!$news) {
            return false;
        }

        UserModel::editRecoverFlag($user_id);

        addMsg(Translate::get('password changed'), 'success');
        redirect(getUrlByName('login'));
    }

    // Проверка корректности E-mail
    public function ActivateEmail()
    {
        $code = Request::get('code');
        $activate_email = UserModel::getEmailActivate($code);

        if (!$activate_email) {
            addMsg(Translate::get('code-used'), 'error');
            redirect('/');
        }

        UserModel::EmailActivate($activate_email['user_id']);

        addMsg(Translate::get('yes-email-pass'), 'success');
        redirect(getUrlByName('login'));
    }
}
