<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\AgentModel;
use Base;

class AgentController extends MainController
{
    public function index()
    {
        $limit      = 100;
        $ua_info    = AgentModel::getAll($limit);

        return $ua_info;
    }

    public function set()
    {
        // https://github.com/donatj/PhpUserAgent
        require_once HLEB_GLOBAL_DIRECTORY . '/app/ThirdParty/PhpUserAgent/UserAgentParser.php';

        $uid        = Base::getUid();
        $ua_info    = parse_user_agent();
        $data = [
            'log_date'          => date("Y-m-d H:i:s"),
            'log_user_id'       => $uid['user_id'],
            'log_user_browser'  => $ua_info['browser'] . ' ' . $ua_info['version'],
            'log_user_os'       => $ua_info['platform'],
            'log_user_ip'       => Request::getRemoteAddress(),
        ];

        AgentModel::setLog($data);
    }
}
