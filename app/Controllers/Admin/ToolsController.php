<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Agouti\Base;

class ToolsController extends MainController
{
    public function index()
    {
        $meta = [
            'meta_title'    => lang('tools'),
            'sheet'         => 'tools',
        ];

        return view('/admin/tools', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
    }
}
