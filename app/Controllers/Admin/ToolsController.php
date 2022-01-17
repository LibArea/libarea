<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Translate, Tpl;

class ToolsController extends MainController
{
    public function index($sheet, $type)
    {
        return Tpl::agRender(
            '/admin/tools/tools',
            [
                'meta'  => meta($m = [], Translate::get('tools')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                ]
            ]
        );
    }
}
