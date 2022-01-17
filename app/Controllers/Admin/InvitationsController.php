<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\User\{InvitationModel, UserModel};
use Translate, Tpl;

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

        return Tpl::agRender(
            '/admin/invitation/invitations',
            [
                'meta'  => meta($m = [], Translate::get('invites')),
                'data'  => [
                    'type'          => 'invites',
                    'sheet'         => 'all',
                    'invitations'   => $result,
                ]
            ]
        );
    }
}
