<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{InvitationModel, UserModel};
use Base, Validation, Translate;

class InvitationsController extends MainController
{
    // Показ формы создания инвайта
    public function inviteForm()
    {
        return view(
            '/user/invite',
            [
                'meta'  => meta($m = [], Translate::get('invite')),
                'uid'   => Base::getUid(),
                'data'  => []
            ]
        );
    }

    // Страница инвайтов пользователя
    function invitationForm()
    {
        // Страница участника и данные
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect(getUrlByName('invitations', ['login' => $uid['user_login']]));
        }

        // Если пользователь забанен
        $user = UserModel::getUser($uid['user_id'], 'id');
        Base::accountBan($user);

        return view(
            '/user/invitation',
            [
                'meta'  => meta($m = [], Translate::get('invites')),
                'uid'   => $uid,
                'data'  => [
                    'invitations'   => InvitationModel::userResult($uid['user_id']),
                    'count_invites' => $user['user_invitation_available'],
                ]
            ]
        );
    }

    // Создать инвайт
    function create()
    {
        // Данные участника
        $uid    = Base::getUid();

        $invitation_email = Request::getPost('email');

        $redirect = getUrlByName('invitations', ['login' => $uid['user_login']]);

        Validation::checkEmail($invitation_email, $redirect);

        $user = UserModel::userInfo($invitation_email);
        if (!empty($user['user_email'])) {

            if ($user['user_email']) {
                addMsg(Translate::get('user-already'), 'error');
                redirect($redirect);
            }
        }

        $inv_user = InvitationModel::duplicate($uid['user_id']);

        if ($inv_user['invitation_email'] == $invitation_email) {
            addMsg(Translate::get('invate-to-replay'), 'error');
            redirect($redirect);
        }

        // + Повторная отправка
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = Base::randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();

        InvitationModel::create($uid['user_id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        addMsg(Translate::get('invite created'), 'success');
        redirect($redirect);
    }
}
