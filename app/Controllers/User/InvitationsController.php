<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{InvitationModel, UserModel};
use Base, Validation, Translate;

class InvitationsController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
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
        if (Request::get('login') != $this->uid['user_login']) {
            redirect(getUrlByName('invitations', ['login' => $this->uid['user_login']]));
        }

        // Если пользователь забанен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);

        return agRender(
            '/user/invitation',
            [
                'meta'  => meta($m = [], Translate::get('invites')),
                'uid'   => $this->uid,
                'data'  => [
                    'invitations'   => InvitationModel::userResult($this->uid['user_id']),
                    'count_invites' => $user['user_invitation_available'],
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

        $redirect = getUrlByName('invitations', ['login' => $this->uid['user_login']]);

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

        addMsg(Translate::get('invite created'), 'success');
        redirect($redirect);
    }
}
