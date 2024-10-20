<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\User\{InvitationModel, UserModel};
use App\Models\Auth\AuthModel;
use App\Bootstrap\Services\Auth\{Action, Register};
use App\Validate\RulesRegistration;
use SendEmail, Meta, Html, Msg;

class RegisterController extends Controller
{
    /**
     * Show registration form
     * Показ формы регистрации
     *
     * @return void
     */
    public function showRegisterForm(): void
    {
        // If the invite system is enabled
        if (config('general', 'invite') == true) {
            redirect(url('invite'));
        }

        $m = [
            'og'    => false,
            'url'   => url('register'),
        ];

        render(
            '/auth/register',
            [
                'meta'  => Meta::get(__('app.registration'), __('help.security_info'), $m),
                'data'  => [
                    'sheet' => 'registration',
                    'type'  => 'register'
                ]
            ]
        );
    }

    /**
     * User registration
     * Регистрация пользователя
     *
     * @return void
     */
    public function index()
    {
        $inv_user_id  = Request::post('invitation_id')->asInt();
        $reg_ip  = Request::getUri()->getIp();
        $data    = Request::allPost();
        $inv_uid = RulesRegistration::rules($data, $reg_ip, $inv_user_id);

        $active_uid = UserModel::create(
            [
                'login'                => $data['login'],
                'email'                => $data['email'],
                'template'             => config('general', 'template'),
                'lang'                 => config('general', 'lang'),
                'whisper'              => '',
                'password'             => password_hash($data['password'], PASSWORD_BCRYPT),
                'limiting_mode'        => 0, // режим заморозки выключен
                'activated'            => Register::activated($inv_uid),
                'reg_ip'               => $reg_ip,
                'trust_level'          => Register::trustLevel(),
                'invitation_id'        => $inv_uid,
            ]
        );

        if ($inv_uid > 0) {
            // If registration by invite, activate the email
            // Если регистрация по инвайту, активируем емайл
            InvitationModel::activate($inv_uid, $active_uid, $data['invitation_code'] ?? null);

            Msg::redirect(__('msg.change_saved'), 'success', url('login'));
        }

        // Email Activation
        $email_code = Html::randomString('crypto', 20);
        AuthModel::sendActivateEmail(
            [
                'user_id'       => $active_uid,
                'email_code'    => $email_code,
            ]
        );

        if (config('general', 'mail_check') === false) {

            InvitationModel::activate($inv_uid, $active_uid, $data['invitation_code'] ?? null);

            Action::set($active_uid);

            redirect('/');
        }

        // Sending email
        SendEmail::mailText($active_uid, 'activate.email', ['link' => url('activate.code', ['code' => $email_code])]);

        Msg::redirect(__('msg.check_your_email'), 'success', url('login'));
    }

    /**
     * Show registration form with invite
     * Показ формы регистрации с инвайтом
     *
     * @return void
     */
    public function showInviteForm(): void
    {
        $code   = Request::param('code')->asString();
        $invate = InvitationModel::available($code);

        if (!$invate) {
            Msg::redirect(__('msg.code_incorrect'), 'error', '/');
        }

        render(
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
