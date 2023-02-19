<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\ActionModel;
use App\Models\User\InvitationModel;
use SendEmail, Meta, Html;

use App\Validate\RulesUserInvitation;

class InvitationsController extends Controller
{
    // Show the form for creating an invite
    public function inviteForm()
    {
        return $this->render(
            '/user/invite',
            [
                'meta'  => Meta::get(__('app.invite')),
                'data'  => []
            ]
        );
    }

    // User invite page
    function invitationForm()
    {
        return $this->render(
            '/user/invitation',
            [
                'meta'  => Meta::get(__('app.invites')),
                'data'  => [
                    'invitations'   => InvitationModel::userResult(),
                    'count_invites' => $this->user['invitation_available'],
                ]
            ]
        );
    }

    function create()
    {
        $invitation_email = Request::getPost('email');

        RulesUserInvitation::rulesInvite($invitation_email, $this->user['invitation_available']);

        $invitation_code = Html::randomString('crypto', 24);

        InvitationModel::create($invitation_code, $invitation_email);

        $this->escort($invitation_code, $invitation_email);

        is_return(__('msg.invite_created'), 'success', url('invitations'));
    }

    // We will send an email and write logs
    function escort($invitation_code, $invitation_email)
    {
        $link = url('invite.reg', ['code' => $invitation_code]);

        SendEmail::mailText($this->user['id'], 'invite.reg', ['link' => $link, 'invitation_email' => $invitation_email]);

        ActionModel::addLogs(
            [
                'id_content'    => 0,
                'action_type'   => 'invite',
                'action_name'   => 'added',
                'url_content'   => $link,
            ]
        );
    }
}
