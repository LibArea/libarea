<?php

namespace Modules\Teams\App;

use Hleb\Constructor\Handlers\Request;
use UserData, Meta, Html;

class Teams
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // All commands created by the user
    // Все команды созданные пользователем
    public function index()
    {
        return view(
          '/view/default/user',
            [
                'meta'  => Meta::get(__('teams')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'user',
                    'teams'     => [],
                ]
            ]
        );
    }
    
    public function add()
    {
        return view(
          '/view/default/add',
            [
                'meta'  => Meta::get(__('add') . ' ' . __('teams')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'add',
                    'teams'     => [],
                ]
            ]
        );
    }
    
    public function edit()
    {
        return view(
          '/view/default/edit',
            [
                'meta'  => Meta::get(__('edit') . ' ' . __('teams')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'edit',
                    'teams'     => [],
                ]
            ]
        );
    }

    public static function create()
    {
        return 'create метод';
    }
    
    public static function change()
    {
        return 'change метод';
    }
}
