<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use App\Middleware\Before\UserData;
use Translate;

class ToolsController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    public function index($sheet, $type)
    {
        return agRender(
            '/admin/tools/tools',
            [
                'meta'  => meta($m = [], Translate::get('tools')),
                'uid'   => $this->uid,
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                ]
            ]
        );
    }
}
