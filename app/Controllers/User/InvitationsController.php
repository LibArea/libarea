<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{InvitationModel, UserModel};
use Validation, Translate, SendEmail, Tpl, UserData;

class InvitationsController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Показ формы создания инвайта
    public function inviteForm()
    {
        return Tpl::agRender(
            '/user/invite',
            [
                'meta'  => meta($m = [], Translate::get('invite')),
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
        return Tpl::agRender(
            '/user/invitation',
            [
                'meta'  => meta($m = [], Translate::get('invites')),
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

        $redirect = getUrlByName('invitations');

        Validation::Email($invitation_email, $redirect);

        $user = UserModel::userInfo($invitation_email);
        if (!empty($user['email'])) {
            addMsg('user-already', 'error');
            redirect($redirect);
        }

        $inv_user = InvitationModel::duplicate($invitation_email);
        if ($inv_user['invitation_email'] == $invitation_email) {
            addMsg('invate-to-replay', 'error');
            redirect($redirect);
        }

        // TODO : + Config::get('invite.limit')
        if ($this->user['invitation_available'] >= 5) {
            addMsg('invate.limit.stop', 'error');
            redirect($redirect);
        }

        $invitation_code = randomString('crypto', 25);

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
        $link = getUrlByName('invite.reg', ['code' => $invitation_code]);
        SendEmail::mailText($this->user['id'], 'invite.reg', ['link' => $link, 'invitation_email' => $invitation_email]);

        addMsg('invite created', 'success');
        redirect($redirect);
    }
}
