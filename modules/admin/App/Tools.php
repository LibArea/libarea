<?php

namespace Modules\Admin\App;

use Translate, Meta;

class Tools
{
    public function index($sheet, $type)
    {
        return view(
            '/view/default/tools/tools',
            [
                'meta'  => Meta::get(Translate::get('tools')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                ]
            ]
        );
    }
}
