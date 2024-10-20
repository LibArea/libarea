<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\User\{UserModel, SettingModel};
use App\Models\Auth\AuthModel;
use App\Validate\Validator;
use App\Content\Integration\Google;
use SendEmail, Meta, Html, Msg;

class RecoverController extends Controller
{
    public function index()
    {
        $redirect  = url('recover');

        if (config('integration', 'captcha')) {
            if (!Google::checkCaptchaCode()) {
                Msg::redirect(__('msg.code_error'), 'error', $redirect);
            }
        }

        Validator::email($email = Request::post('email')->value(), $redirect);

        $uInfo = AuthModel::getUser($email, 'email');

        if (empty($uInfo['email'])) {
            Msg::redirect(__('msg.no_user'), 'error', $redirect);
        }

        if ($uInfo['ban_list'] == 1) {
            Msg::redirect(__('msg.account_verified'), 'error', $redirect);
        }

        $code = $uInfo['id'] . '-' . Html::randomString('crypto', 24);
        AuthModel::initRecover(
            [
                'activate_date'     => date('Y-m-d H:i:s'),
                'activate_user_id'  => $uInfo['id'],
                'activate_code'     => $code,
            ]
        );

        SendEmail::mailText($uInfo['id'], 'changing.password', ['newpass_link' => url('recover.code', ['code' => $code])]);

        Msg::redirect(__('msg.new_password_email'), url('login'));
    }

    /**
     * Password Change page
     * Страница смены пароля
     *
     * @return void
     */
    public function showPasswordForm(): void
    {
        $m = [
            'og'    => false,
            'url'   => url('recover'),
        ];

        render(
            '/auth/recover',
            [
                'meta'  => Meta::get(__('app.password_recovery'), __('app.recover_info'), $m),
                'data'  => [
                    'sheet' => 'recover',
                    'type'  => 'recover',
                ]
            ]
        );
    }

    /**
     * The page for setting a new password
     * Страница установки нового пароля
     *
     * @return void
     */
    public function showRemindForm(): void
    {
        $code       = Request::param('code')->asString();
        $user_id    = AuthModel::getPasswordActivate($code);

        if (!$user_id) {
            Msg::add(__('msg.went_wrong'), 'error');
            redirect(url('login'));
        }

        $user = UserModel::get($user_id['activate_user_id'], 'id');
        notEmptyOrView404($user);

        render(
            '/auth/newrecover',
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
        $password   = Request::post('password')->value();
        $code       = Request::post('code')->value();
        $user_id    = Request::post('user_id')->asInt();

        if (!$user_id) {
            return false;
        }

        Validator::length($password, 8, 32, 'password', url('recover.code', ['code' => $code]));

        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        SettingModel::editPassword(['id' => $user_id, 'password' => $newpass]);

        AuthModel::editRecoverFlag($user_id);

        Msg::redirect(__('msg.change_saved'), 'success', url('login'));

        return true;
    }

    /**
     * Checking the correctness of the Email
     * Проверка корректности E-mail
     *
     * @return void
     */
    public function activateEmail()
    {
        $code = Request::param('code')->asString();
        $activate_email = AuthModel::getEmailActivate($code);

        if (!$activate_email) {
            Msg::add(__('msg.code_incorrect'), 'error');
            redirect('/');
        }

        AuthModel::setEmailActivate($activate_email['user_id']);

        Msg::add(__('msg.yes_email_pass'), 'success');
        redirect(url('login'));
    }
}
