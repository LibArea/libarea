<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{InvitationModel, UserModel};
use Validation, SendEmail, Meta, Html;

class InvitationsController extends Controller
{
    // Показ формы создания инвайта
    public function inviteForm()
    {
        return $this->render(
            '/user/invite',
            [
                'meta'  => Meta::get(__('app.invite')),
                'data'  => [
                    'sheet' => 'invite',
                    'type'  => 'user',
                ]
            ]
        );
    }

    // Страница инвайтов пользователя
    function invitationForm()
    {
        return $this->render(
            '/user/invitation',
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

    // Создать инвайт
    function create()
    {
        $invitation_email = Request::getPost('email');

        $redirect = url('invitations');

        if (!filter_var($invitation_email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(['error' => 'error', 'text' => __('msg.email_correctness')]);
        }
        

        $user = UserModel::userInfo($invitation_email);
        if (!empty($user['email'])) {
            Validation::ComeBack('msg.user_already', 'error', $redirect);
        }

        $inv_user = InvitationModel::duplicate($invitation_email);
        if ($inv_user['invitation_email'] == $invitation_email) {
            Validation::ComeBack('msg.invate_replay', 'error', $redirect);
        }

        if ($this->user['invitation_available'] >= config('general.invite_limit')) {
            Validation::ComeBack('msg.invate_limit_stop', 'error', $redirect);
        }

        $invitation_code = Html::randomString('crypto', 25);

        InvitationModel::create(
            [
                'uid'               => $this->user['id'],
                'invitation_code'   => $invitation_code,
                'invitation_email'  => $invitation_email,
                'add_time'          => date('Y-m-d H:i:s'),
                'add_ip'            => Request::getRemoteAddress(),
            ]
        );

        // Отправка e-mail
        $link = url('invite.reg', ['code' => $invitation_code]);
        SendEmail::mailText($this->user['id'], 'invite.reg', ['link' => $link, 'invitation_email' => $invitation_email]);

        Validation::ComeBack('msg.invite_created', 'success', $redirect);
    }
}
