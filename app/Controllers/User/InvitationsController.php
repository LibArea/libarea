<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\{InvitationModel, UserModel};
use Validation, Translate, Config, SendEmail;

class InvitationsController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Показ формы создания инвайта
    public function inviteForm()
    {
        return agRender(
            '/user/invite',
            [
                'meta'  => meta($m = [], Translate::get('invite')),
                'uid'   => $this->uid,
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
        return agRender(
            '/user/invitation',
            [
                'meta'  => meta($m = [], Translate::get('invites')),
                'uid'   => $this->uid,
                'data'  => [
                    'invitations'   => InvitationModel::userResult($this->uid['user_id']),
                    'count_invites' => $this->uid['user_invitation_available'],
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

        Validation::checkEmail($invitation_email, $redirect);

        $user = UserModel::userInfo($invitation_email);
        if (!empty($user['user_email'])) {

            if ($user['user_email']) {
                addMsg(Translate::get('user-already'), 'error');
                redirect($redirect);
            }
        }

        $inv_user = InvitationModel::duplicate($this->uid['user_id']);

        if ($inv_user['invitation_email'] == $invitation_email) {
            addMsg(Translate::get('invate-to-replay'), 'error');
            redirect($redirect);
        }

        // + Повторная отправка
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();

        InvitationModel::create($this->uid['user_id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        // Отправка e-mail
        $link = getUrlByName('invite.reg', ['code' => $invitation_code]);
        SendEmail::mailText($this->uid['user_id'], 'invite.reg', ['link' => $link, 'invitation_email' => $invitation_email]);

        addMsg(Translate::get('invite created'), 'success');
        redirect($redirect);
    }
}
