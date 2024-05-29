<?php

declare(strict_types=1);

namespace App\Validate;

use App\Models\User\InvitationModel;
use App\Models\Auth\AuthModel;
use Msg;

class RulesUserInvitation extends Validator
{
    public static function rulesInvite(string $email, int $quantity)
    {
        $redirect = url('invitations');

        self::email($email, $redirect);

        $user = AuthModel::getUser($email, 'email');
        if (!empty($user['email'])) {
            Msg::redirect(__('msg.user_already'), 'error', $redirect);
        }

        $inv_user = InvitationModel::duplicate($email);
        if (!empty($inv_user['invitation_email'])) {
            if ($inv_user['invitation_email'] == $email) {
                Msg::redirect(__('msg.invate_replay'), 'error', $redirect);
            }
        }

        if ($quantity >= config('trust-levels', 'perDay_invite')) {
            Msg::redirect(__('msg.invate_limit_stop'), 'error', $redirect);
        }

        return true;
    }
}
