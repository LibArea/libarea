<?php

namespace Modules\Teams\App;

use Hleb\Constructor\Handlers\Request;
use UserData, Meta;

class section
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index()
    {
        return view(
            '/view/default/user',
            [
                'meta'  => Meta::get(__('teams'), __('teams')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'teams',
                    'teams'     => [],
                ]
            ]
        );
    }
}
