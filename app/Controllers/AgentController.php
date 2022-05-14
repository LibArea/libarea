<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;

class AgentController extends Controller
{
    public function set($uid)
    {
        $info    = parse_user_agent();
        UserModel::setLogAgent(
            [
                'user_id'       => $uid,
                'user_browser'  => $info['browser'] . ' ' . $info['version'],
                'user_os'       => $info['platform'],
                'user_ip'       => Request::getRemoteAddress(),
            ]
        );
    }
}
