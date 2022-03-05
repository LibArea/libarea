<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;

class AgentController extends MainController
{
    public function set($uid)
    {
        // https://github.com/donatj/PhpUserAgent
        require_once HLEB_GLOBAL_DIRECTORY . '/app/ThirdParty/PhpUserAgent/UserAgentParser.php';

        $ua_info    = parse_user_agent();
        UserModel::setLogAgent(
            [
                'user_id'       => $uid,
                'user_browser'  => $ua_info['browser'] . ' ' . $ua_info['version'],
                'user_os'       => $ua_info['platform'],
                'user_ip'       => Request::getRemoteAddress(),
            ]
        );
    }
}
