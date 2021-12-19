<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Base, Translate;

class ToolsController extends MainController
{
    public function index($sheet, $type)
    {
        return render(
            '/admin/tools/tools',
            [
                'meta'  => meta($m = [], Translate::get('tools')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                ]
            ]
        );
    }
}
