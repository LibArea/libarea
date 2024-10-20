<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;
use Modules\Admin\Models\{InvitationModel, UserModel};
use Meta;

class InvitationsController extends Module
{
    public function index(): View
    {
        $invite = InvitationModel::get();

        $result = [];
        foreach ($invite  as $ind => $row) {
            $row['uid']         = UserModel::getUser($row['uid'], 'id');
            $result[$ind]       = $row;
        }

        return view(
            '/invitation/invitations',
            [
                'meta'  => Meta::get(__('admin.invites')),
                'data'  => [
                    'type'          => 'invitations',
                    'sheet'         => 'invitations',
                    'invitations'   => $result,
                ]
            ]
        );
    }
}
