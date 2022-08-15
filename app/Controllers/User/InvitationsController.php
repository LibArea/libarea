<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\ActionModel;
use App\Models\User\{InvitationModel, UserModel};
use Validation, SendEmail, Meta, Html;

class InvitationsController extends Controller
{
    // Show the form for creating an invite
    public function inviteForm()
    {
        return $this->render(
            '/user/invite',
            'base',
            [
                'meta'  => Meta::get(__('app.invite')),
                'data'  => [
                    'sheet' => 'invite',
                    'type'  => 'user',
                ]
            ]
        );
    }

    // User invite page
    function invitationForm()
    {
        return $this->render(
            '/user/invitation',
            'base',
            [
                'meta'  => Meta::get(__('app.invites')),
                'data'  => [
                    'invitations'   => InvitationModel::userResult($this->user['id']),
                    'count_invites' => $this->user['invitation_available'],
                    'sheet' => 'invites',
                    'type'  => 'user',
                ]
            ]
        );
    }

    function create()
    {
        $invitation_email = Request::getPost('email');

        $redirect = url('invitations');

        Validation::email(Request::getPost('email'), $redirect);

        $user = UserModel::userInfo($invitation_email);
        if (!empty($user['email'])) {
            is_return(__('msg.user_already'), 'error', $redirect);
        }

        $inv_user = InvitationModel::duplicate($invitation_email);
        if (!empty($inv_user['invitation_email'])) {
            if ($inv_user['invitation_email'] == $invitation_email) {
                is_return(__('msg.invate_replay'), 'error', $redirect);
            }
        }

        if ($this->user['invitation_available'] >= config('general.invite_limit')) {
            is_return(__('msg.invate_limit_stop'), 'error', $redirect);
        }

        $invitation_code = Html::randomString('crypto', 24);

        InvitationModel::create(
            [
                'uid'               => $this->user['id'],
                'invitation_code'   => $invitation_code,
                'invitation_email'  => $invitation_email,
                'add_ip'            => Request::getRemoteAddress(),
            ]
        );

        $this->escort($invitation_code, $invitation_email);

        is_return(__('msg.invite_created'), 'success', $redirect);
    }
    
    // We will send an email and write logs
    function escort ($invitation_code, $invitation_email)
    {
        $link = url('invite.reg', ['code' => $invitation_code]);

        SendEmail::mailText($this->user['id'], 'invite.reg', ['link' => $link, 'invitation_email' => $invitation_email]);

        ActionModel::addLogs(
            [
                'id_content'    => $invitation_email,
                'action_type'   => 'invite',
                'action_name'   => 'added',
                'url_content'   => $link,
            ]
        );
    }
    
}
