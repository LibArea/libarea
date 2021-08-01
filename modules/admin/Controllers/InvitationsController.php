<?php

namespace Modules\Admin\Controllers;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\InvitationModel;
use App\Models\UserModel;
use Lori\Base;

class InvitationsController extends \MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $invite = InvitationModel::getInvitations();

        $result = array();
        foreach ($invite  as $ind => $row) {
            $row['uid']         = UserModel::getUser($row['uid'], 'id');
            $row['active_time'] = $row['active_time'];
            $result[$ind]       = $row;
        }

        $data = [
            'meta_title'    => lang('Invites'),
            'sheet'         => 'invitations',
        ];

        return view('/templates/invitations', ['data' => $data, 'uid' => $uid, 'invitations' => $result]);
    }
}
