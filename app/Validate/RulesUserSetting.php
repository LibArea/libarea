<?php

declare(strict_types=1);

namespace App\Validate;

use App\Models\Auth\AuthModel;
use Msg;

class RulesUserSetting extends Validator
{
    public static function rulesSetting($data)
    {
        $redirect = url('setting');

        self::length($data['name'], 0, 11, 'name', $redirect);
        self::length($data['about'], 0, 255, 'about', $redirect);

        if ($data['public_email']) {
            self::email($data['public_email'], $redirect);
        }

        return true;
    }

    public static function rulesSecurity(array $data, string $email)
    {
        $redirect   = '/setting/security';

        if ($data['password2'] != $data['password3']) {
            Msg::redirect(__('msg.pass_match_err'), 'error', $redirect);
        }

        if (substr_count($data['password2'], ' ') > 0) {
            Msg::redirect(__('msg.password_spaces'), 'error', $redirect);
        }

        self::length($data['password2'], 8, 32, 'password', $redirect);

        $userInfo   = AuthModel::getUser($email, 'email');
        if (!password_verify($data['password'], $userInfo['password'])) {
            Msg::redirect(__('msg.old_error'), 'error', $redirect);
        }

        return true;
    }
    
    
    public static function rulesNewEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
