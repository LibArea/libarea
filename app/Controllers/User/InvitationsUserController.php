<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Lori\{Config, Base, Validation};

class InvitationsUserController extends MainController
{
    // Показ формы создания инвайта
    public function inviteForm()
    {
        $meta = [
            'sheet'         => 'invite',
            'meta_title'    => lang('Invite'),
        ];

        return view('/user/invite', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
    }

    // Страница инвайтов пользователя
    function invitationForm()
    {
        // Страница участника и данные
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/invitation');
        }

        // Если пользователь забанен
        $user = UserModel::getUser($uid['user_id'], 'id');
        Base::accountBan($user);

        $meta = [
            'sheet'         => 'invites',
            'meta_title'    => lang('Invites') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        $data = [
            'invitations'   => UserModel::InvitationResult($uid['user_id']),
            'count_invites' => $user['user_invitation_available'],
        ];

        return view('/user/invitation', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Создать инвайт
    function invitationCreate()
    {
        // Данные участника
        $uid    = Base::getUid();

        $invitation_email = Request::getPost('email');

        $redirect = '/u/' . $uid['user_login'] . '/invitation';

        Validation::checkEmail($invitation_email, $redirect);

        $user = UserModel::userInfo($invitation_email);
        if (!empty($user['user_email'])) {

            if ($user['user_email']) {
                Base::addMsg(lang('user-already'), 'error');
                redirect($redirect);
            }
        }

        $inv_user = UserModel::InvitationOne($uid['user_id']);

        if ($inv_user['invitation_email'] == $invitation_email) {
            Base::addMsg(lang('invate-to-replay'), 'error');
            redirect($redirect);
        }

        // + Повторная отправка
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = Base::randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();

        UserModel::addInvitation($uid['user_id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        Base::addMsg(lang('Invite created'), 'success');
        redirect($redirect);
    }
}
