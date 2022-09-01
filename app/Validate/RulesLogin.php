<?php

namespace App\Validate;

use App\Models\User\UserModel;

class RulesLogin extends Validator
{
    public static function rules($data)
    {
        $redirect   = url('login');

        self::email($data['email'], $redirect);

        $user = UserModel::userInfo($data['email']);

        if (empty($user['id'])) {
            is_return(__('msg.no_user'), 'error', $redirect);
        }

        // Is it on the ban list
        // Находится ли в бан- листе
        if (UserModel::isBan($user['id'])) {
            is_return(__('msg.account_verified'), 'error', $redirect);
        }

        if (!UserModel::isActivated($user['id'])) {
            is_return(__('msg.not_activated'), 'error', $redirect);
        }

        if (!password_verify($data['password'], $user['password'])) {
            is_return(__('msg.not_correct'), 'error', $redirect);
        }

        return $user;
    }
}
