<?php

namespace Modules\Admin\App;

use Meta;

class Tools
{
    public function index($sheet, $type)
    {
        return view(
            '/view/default/tools/tools',
            [
                'meta'  => Meta::get(__('admin.tools')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                ]
            ]
        );
    }
}
