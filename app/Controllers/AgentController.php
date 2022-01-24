<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\UserModel;

class AgentController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function set()
    {
        // https://github.com/donatj/PhpUserAgent
        require_once HLEB_GLOBAL_DIRECTORY . '/app/ThirdParty/PhpUserAgent/UserAgentParser.php';

        $ua_info    = parse_user_agent();
        $data = [
            'log_date'          => date("Y-m-d H:i:s"),
            'log_user_id'       => $this->user['id'],
            'log_user_browser'  => $ua_info['browser'] . ' ' . $ua_info['version'],
            'log_user_os'       => $ua_info['platform'],
            'log_user_ip'       => Request::getRemoteAddress(),
        ];

        UserModel::setLogAgent($data);
    }
}
