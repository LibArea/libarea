<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Base;

class ToolsController extends MainController
{
    public function index()
    {
        $meta = meta($m = [], lang('tools'));

        return view('/admin/tools', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
    }
}
