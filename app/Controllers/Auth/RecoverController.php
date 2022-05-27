<?php

namespace App\Controllers\Auth;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{SettingModel, UserModel};
use Integration, Validation, SendEmail, Meta, Html, UserData;

class RecoverController extends Controller
{
    public function showPasswordForm()
    {
        $m = [
            'og'    => false,
            'url'   => url('recover'),
        ];

        return $this->render(
            '/auth/recover',
            'base',
            [
                'meta'  => Meta::get(__('app.password_recovery'), __('app.recover_info'), $m),
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
        $recover_uri    = url('recover');

        if (config('general.captcha')) {
            if (!Integration::checkCaptchaCode()) {
                return json_encode(['error' => 'error', 'text' => __('msg.code_error')]);
            }
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(['error' => 'error', 'text' => __('msg.email_correctness')]);
        }

        $uInfo = UserModel::userInfo($email);

        if (empty($uInfo['email'])) {
            return json_encode(['error' => 'error', 'text' => __('msg.no_user')]);
        }

        // Проверка на заблокированный аккаунт
        if ($uInfo['ban_list'] == UserData::BANNED_USER) {
            return json_encode(['error' => 'error', 'text' => __('msg.account_verified')]);
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
        SendEmail::mailText($uInfo['id'], 'changing.password', ['newpass_link' => url('recover.code', ['code' => $code])]);

        return true;
    }

    // Страница установки нового пароля
    public function showRemindForm()
    {
        $code       = Request::get('code');
        $user_id    = UserModel::getPasswordActivate($code);

        if (!$user_id) {
            Html::addMsg(__('msg.went_wrong'), 'error');
            redirect(url('login'));
        }

        $user = UserModel::getUser($user_id['activate_user_id'], 'id');
        self::error404($user);

        return $this->render(
            '/auth/newrecover',
            'base',
            [
                'meta'  => Meta::get(__('app.password recovery'), __('app.recover_info')),
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

        if (!Validation::length($password, 8, 32)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.password') . '»'])]);
        }

        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        SettingModel::editPassword(['id' => $user_id, 'password' => $newpass]);

        UserModel::editRecoverFlag($user_id);

        return true;
    }

    // Проверка корректности E-mail
    public function ActivateEmail()
    {
        $code = Request::get('code');
        $activate_email = UserModel::getEmailActivate($code);

        if (!$activate_email) {
            Html::addMsg(__('msg.code_incorrect'), 'error');
            redirect('/');
        }

        UserModel::EmailActivate($activate_email['user_id']);

        Html::addMsg(__('msg.yes_email_pass'), 'success');
        redirect(url('login'));
    }
}
