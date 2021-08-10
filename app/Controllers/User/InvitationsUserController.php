<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Lori\Config;
use Lori\Base;

class InvitationsUserController extends \MainController
{
    // Показ формы создания инвайта
    public function inviteForm()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Invite'),
            'sheet'         => 'invite',
            'meta_title'    => lang('Invite'),
        ];

        return view(PR_VIEW_DIR . '/user/invite', ['data' => $data, 'uid' => $uid]);
    }

    // Страница инвайтов пользователя
    function invitationForm()
    {
        // Страница участника и данные
        $uid    = Base::getUid();
        $login  = \Request::get('login');

        if ($login != $uid['login']) {
            redirect('/u/' . $uid['login'] . '/invitation');
        }

        $invitations = UserModel::InvitationResult($uid['id']);

        // Если пользователь забанен
        $user = UserModel::getUser($uid['id'], 'id');
        Base::accountBan($user);

        $data = [
            'h1'            => lang('Invites'),
            'sheet'         => 'invites',
            'meta_title'    => lang('Invites') . ' | ' . Config::get(Config::PARAM_NAME),
            'invitations'   => $invitations,
            'count_invites' => $user['invitation_available'],
        ];

        return view(PR_VIEW_DIR . '/user/invitation', ['data' => $data, 'uid' => $uid]);
    }

    // Создать инвайт
    function invitationCreate()
    {
        // Данные участника
        $uid    = Base::getUid();

        $invitation_email = \Request::getPost('email');

        $redirect = '/u/' . $uid['login'] . '/invitation';

        if (!filter_var($invitation_email, FILTER_VALIDATE_EMAIL)) {
            Base::addMsg(lang('Invalid') . ' email', 'error');
            redirect($redirect);
        }

        $uInfo = UserModel::userInfo($invitation_email);
        if (!empty($uInfo['email'])) {

            if ($uInfo['email']) {
                Base::addMsg(lang('user-already'), 'error');
                redirect($redirect);
            }
        }

        $inv_user = UserModel::InvitationOne($uid['id']);

        if ($inv_user['invitation_email'] == $invitation_email) {
            Base::addMsg(lang('invate-to-replay'), 'error');
            redirect($redirect);
        }

        // + Повторная отправка
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = Base::randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();

        UserModel::addInvitation($uid['id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        Base::addMsg(lang('Invite created'), 'success');
        redirect($redirect);
    }
}
