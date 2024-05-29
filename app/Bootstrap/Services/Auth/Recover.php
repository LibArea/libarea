<?php

declare(strict_types=1);

namespace App\Bootstrap\Services\Auth;

use Hleb\Static\Request;
use App\Models\Auth\AuthModel;
use App\Validate\Validator;
use App\Content\Integration\Google;
use App\Bootstrap\Services\Auth\RegType;
use SendEmail, Html, Msg;

class Recover
{
    public function index()
    {
        $email      = Request::post('email');
        $redirect   = url('recover');

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

        // Проверка на заблокированный аккаунт
        if ($uInfo['ban_list'] == RegType::BANNED_USER) {
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

        // Отправка e-mail
        SendEmail::mailText($uInfo['id'], 'changing.password', ['newpass_link' => url('recover.code', ['code' => $code])]);

        Msg::redirect(__('msg.new_password_email'), url('login'));
    }
}
