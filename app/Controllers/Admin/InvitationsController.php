<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\UserModel;
use Agouti\Base;

class InvitationsController extends MainController
{
    public function index($sheet)
    {
        $invite = UserModel::getInvitations();

        $result = array();
        foreach ($invite  as $ind => $row) {
            $row['uid']         = UserModel::getUser($row['uid'], 'id');
            $row['active_time'] = $row['active_time'];
            $result[$ind]       = $row;
        }

        $meta = [
            'meta_title'    => lang('Invites'),
            'sheet'         => 'invitations',
        ];

        $data = [
            'sheet'         => $sheet == 'all' ? 'invitations' : $sheet,
            'invitations'   => $result,
        ];

        return view('/admin/invitation/invitations', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
