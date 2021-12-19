<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\User\{InvitationModel, UserModel};
use Base, Translate;

class InvitationsController extends MainController
{
    public function index()
    {
        $invite = InvitationModel::get();

        $result = [];
        foreach ($invite  as $ind => $row) {
            $row['uid']         = UserModel::getUser($row['uid'], 'id');
            $row['active_time'] = $row['active_time'];
            $result[$ind]       = $row;
        }

        return agRender(
            '/admin/invitation/invitations',
            [
                'meta'  => meta($m = [], Translate::get('invites')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'type'          => 'invites',
                    'sheet'         => 'all',
                    'invitations'   => $result,
                ]
            ]
        );
    }
}
