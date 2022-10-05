<?php

namespace Modules\Admin\App;

use Meta;

class Setting
{
    public function index()
    {
        return view(
            '/view/default/setting/general',
            [
                'meta'  => Meta::get(__('admin.users')),
                'data'  => [
                    'type'  => 'settings',
                ]
            ]
        );
    }
    
    public function interface()
    {
        return view(
            '/view/default/setting/interface',
            [
                'meta'  => Meta::get(__('admin.users')),
                'data'  => [
                    'type'  => 'interface',
                ]
            ]
        );
    }
}
