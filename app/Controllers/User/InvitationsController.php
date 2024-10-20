<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\ActionModel;
use App\Models\User\InvitationModel;
use SendEmail, Meta, Html, Msg;

use App\Validate\RulesUserInvitation;

class InvitationsController extends Controller
{
    /**
     * Show the form for creating an invite
     *
     * @return void
     */
    public function inviteForm()
    {
        render(
            '/user/invite',
            [
                'meta'  => Meta::get(__('app.invite')),
                'data'  => []
            ]
        );
    }

    /**
     * User invite page
     *
     * @return void
     */
    function invitationForm()
    {
        render(
            '/user/invitation',
            [
                'meta'  => Meta::get(__('app.invites')),
                'data'  => [
                    'invitations'   => InvitationModel::userResult(),
                    'count_invites' => $this->container->user()->get()['invitation_available'],
                ]
            ]
        );
    }

    function add()
    {
        $invitation_email = Request::post('email')->value();

        RulesUserInvitation::rulesInvite($invitation_email, $this->container->user()->get()['invitation_available']);

        $invitation_code = Html::randomString('crypto', 24);

        InvitationModel::create($invitation_code, $invitation_email);

        $this->escort($invitation_code, $invitation_email);

        Msg::redirect(__('msg.invite_created'), 'success', url('invitations'));
    }

    /**
     * We will send an email and write logs
     *
     * @param string $invitation_code
     * @param string $invitation_email
     * @return void
     */
    function escort(string $invitation_code, string $invitation_email)
    {
        $link = url('invite.reg', ['code' => $invitation_code]);

        SendEmail::mailText($this->container->user()->id(), 'invite.reg', ['link' => $link, 'invitation_email' => $invitation_email]);

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
