<?php

namespace App\Validate;

use App\Models\User\UserModel;

class RulesUserSetting extends Validator
{
    public static function rulesSetting($data)
    {
        $redirect = url('setting');
        
        self::length($data['name'], 5, 11, 'name', $redirect);
        self::length($data['about'], 5, 255, 'about', $redirect);

        if ($data['public_email']) {
            self::email($data['public_email'], $redirect);
        }

        return true;
    }
    
    public static function rulesSecurity($data, $email)
    {
        $redirect   = '/setting/security';
        
        if ($data['password2'] != $data['password3']) {
            is_return(__('msg.pass_match_err'), 'error', $redirect);
        }

        if (substr_count($data['password2'], ' ') > 0) {
            is_return(__('msg.password_spaces'), 'error', $redirect);
        }

        self::length($data['password2'], 8, 32, 'password', $redirect);

        $userInfo   = UserModel::userInfo($email);
        if (!password_verify($data['password'], $userInfo['password'])) {
            is_return(__('msg.old_error'), 'error', $redirect);
        }

        return true;
    }
}
