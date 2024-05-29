<?php

declare(strict_types=1);

namespace App\Validate;

use App\Models\Auth\AuthModel;
use Msg;

class RulesLogin extends Validator
{
    public static function rules($data)
    {
        $redirect = url('login');

        self::email($data['email'], $redirect);

        $user = AuthModel::getUser($data['email'], 'email');

        if (empty($user['id'])) {
            Msg::redirect(__('msg.no_user'), 'error', $redirect);
        }

        // Is it on the ban list
        // Находится ли в бан- листе
        if (AuthModel::isBan($user['id'])) {
            Msg::redirect(__('msg.account_verified'), 'error', $redirect);
        }

        if (!AuthModel::isActivated($user['id'])) {
            Msg::redirect(__('msg.not_activated'), 'error', $redirect);
        }

        if (AuthModel::isDeleted($user['id'])) {
            Msg::redirect(__('msg.no_user'), 'error', '/');
        }

        if (!password_verify($data['password'], $user['password'])) {
            Msg::redirect(__('msg.not_correct'), 'error', $redirect);
        }

        return $user;
    }
}
