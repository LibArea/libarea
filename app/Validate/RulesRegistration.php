<?php

namespace App\Validate;

use App\Models\AuthModel;
use App\Services\Сheck\EmailSpam;
use App\Services\Integration\Google;

class RulesRegistration extends Validator
{
    public static function rules($data, $reg_ip, $inv_uid)
    {
        $invitation_code = $data['invitation_code'] ?? false;
        $redirect = $invitation_code ? '/register/invite/' . $invitation_code : url('register');

        // Check ip for ban
        // Запрет Ip на бан
        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            is_return(__('msg.multiple_accounts'), 'error', $redirect);
        }

        // Let's check the verification code
        // Проверим код проверки
        if (!$invitation_code) {
            if (config('integration.captcha')) {
                if (!Google::checkCaptchaCode()) {
                    is_return(__('msg.code_error'), 'error', $redirect);
                }
            }
            // Если хакинг формы (If form hacking)
            $inv_uid = 0;
        }

        // Check login
        // Проверим login
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $data['login'])) {
            is_return(__('msg.slug_correctness'), 'error', $redirect);
        }

        self::length($data['login'], 3, 12, 'nickname', $redirect);

        if (preg_match('/(\w)\1{3,}/', $data['login'])) {
            is_return(__('msg.nick_character'), 'error', $redirect);
        }

        if (in_array($data['login'], config('stop-nickname'))) {
            is_return(__('msg.nick_exist'), 'error', $redirect);
        }

        if (is_array(AuthModel::checkRepetitions($data['login'], 'login'))) {
            is_return(__('msg.nick_exist'), 'error', $redirect);
        }

        // Check Email
        // Проверим Email
        self::email($data['email'], $redirect);

        if (EmailSpam::index($data['email']) === true) {
            is_return(__('msg.email_forbidden'), 'error', $redirect);
        } 

        if (is_array(AuthModel::checkRepetitions($data['email'], 'email'))) {
            is_return(__('msg.email_replay'), 'error', $redirect);
        }

        $arr = explode('@', $data['email']);
        $domain = array_pop($arr);
        if (in_array($domain, config('stop-email'))) {
            is_return(__('msg.email_replay'), 'error', $redirect);
        }

        // Let's check the password
        // Проверим пароль
        self::length($data['password'], 8, 32, 'password', $redirect);

        if (substr_count($data['password'], ' ') > 0) {
            is_return(__('msg.password_spaces'), 'error', $redirect);
        }

        if ($data['password'] != $data['password_confirm']) {
            is_return(__('msg.pass_match_err'), 'error', $redirect);
        }

        return $inv_uid;
    }
}
