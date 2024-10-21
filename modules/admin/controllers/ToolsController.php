<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;
use Meta;

class ToolsController extends Module
{
    public function index(): View
    {
        return view(
            '/tools/tools',
            [
                'meta'  => Meta::get(__('admin.tools')),
                'data'  => [
                    'type'      => 'tools',
                ]
            ]
        );
    }
}
