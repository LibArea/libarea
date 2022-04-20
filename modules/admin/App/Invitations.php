<?php

namespace Modules\Admin\App;

use App\Models\User\{InvitationModel, UserModel};
use Meta;

class Invitations
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

        return view(
            '/view/default/invitation/invitations',
            [
                'meta'  => Meta::get(__('invites')),
                'data'  => [
                    'type'          => 'invitations',
                    'sheet'         => 'invitations',
                    'invitations'   => $result,
                ]
            ]
        );
    }
}
