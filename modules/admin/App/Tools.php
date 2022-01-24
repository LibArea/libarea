<?php

namespace Modules\Admin\App;

use Translate;

class Tools
{
    public function index($sheet, $type)
    {
        return view(
            '/view/default/tools/tools',
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
