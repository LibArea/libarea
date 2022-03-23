<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{SettingModel, UserModel};
use Config, Integration, Validation, SendEmail, Translate, Tpl, Meta, Html, UserData;

class RecoverController extends MainController
{
    public function showPasswordForm()
    {
        $m = [
            'og'    => false,
            'url'   => getUrlByName('recover'),
        ];

        return Tpl::agRender(
            '/auth/recover',
            [
                'meta'  => Meta::get($m, Translate::get('password recovery'), Translate::get('info-recover')),
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
                Html::addMsg('code.error', 'error');
                redirect($recover_uri);
            }
        }

        Validation::Email($email, $recover_uri);

        $uInfo = UserModel::userInfo($email);

        if (empty($uInfo['email'])) {
            Html::addMsg('email.no.site', 'error');
            redirect($recover_uri);
        }

        // Проверка на заблокированный аккаунт
        if ($uInfo['ban_list'] == UserData::BANNED_USER) {
            Html::addMsg('account.being.verified', 'error');
            redirect($recover_uri);
        }

        $code = $uInfo['id'] . '-' . Html::randomString('crypto', 25);
        UserModel::initRecover(
            [
                'activate_date'     => date('Y-m-d H:i:s'),
                'activate_user_id'  => $uInfo['id'],
                'activate_code'     => $code,
            ]
        );

        // Отправка e-mail
        SendEmail::mailText($uInfo['id'], 'changing.password', ['newpass_link' => getUrlByName('recover.code', ['code' => $code])]);

        Html::addMsg('new.password.email', 'success');
        redirect(getUrlByName('login'));
    }

    // Страница установки нового пароля
    public function showRemindForm()
    {
        $code       = Request::get('code');
        $user_id    = UserModel::getPasswordActivate($code);

        if (!$user_id) {
            Html::addMsg('code-incorrect', 'error');
            redirect(getUrlByName('recover'));
        }

        $user = UserModel::getUser($user_id['activate_user_id'], 'id');
        Html::pageError404($user);

        return Tpl::agRender(
            '/auth/newrecover',
            [
                'meta'  => Meta::get($m = [], Translate::get('password recovery'), Translate::get('info-recover')),
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

        Validation::Length($password, Translate::get('password'), '8', '32', getUrlByName('recover.code', ['code' => $code]));

        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        $news     = SettingModel::editPassword(['id' => $user_id, 'password' => $newpass]);

        if (!$news) {
            return false;
        }

        UserModel::editRecoverFlag($user_id);

        Html::addMsg('password changed', 'success');
        redirect(getUrlByName('login'));
    }

    // Проверка корректности E-mail
    public function ActivateEmail()
    {
        $code = Request::get('code');
        $activate_email = UserModel::getEmailActivate($code);

        if (!$activate_email) {
            Html::addMsg('code-used', 'error');
            redirect('/');
        }

        UserModel::EmailActivate($activate_email['user_id']);

        Html::addMsg('yes-email-pass', 'success');
        redirect(getUrlByName('login'));
    }
}
