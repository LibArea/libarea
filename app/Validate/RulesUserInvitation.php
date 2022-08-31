<?php

namespace App\Validate;

use App\Models\User\{UserModel, InvitationModel};

class RulesUserInvitation extends Validator
{
    public static function rulesInvite($email, $quantity)
    {
        $redirect = url('invitations');

        self::email($email, $redirect);

        $user = UserModel::userInfo($email);
        if (!empty($user['email'])) {
            is_return(__('msg.user_already'), 'error', $redirect);
        }

        $inv_user = InvitationModel::duplicate($email);
        if (!empty($inv_user['invitation_email'])) {
            if ($inv_user['invitation_email'] == $email) {
                is_return(__('msg.invate_replay'), 'error', $redirect);
            }
        }

        if ($quantity >= config('general.invite_limit')) {
            is_return(__('msg.invate_limit_stop'), 'error', $redirect);
        }

        return true;
    }

}
