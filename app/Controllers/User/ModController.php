<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
use UserData, Meta;

class ModController extends Controller
{
    // TODO: Login for mods
    // Route::get('/mod/setting/user/{id?}')->controller('User\ModController')->where(['id' => '[0-9]+'])->name('mod.setting'); 
    function index()
    {
        if (!UserData::checkAdmin()) {
            redirect('/');
        }

        return $this->render(
            '/user/setting/setting',
            'base',
            [
                'meta'  => Meta::get(__('app.setting')),
                'data'  => [
                    'sheet' => 'settings',
                    'type'  => 'user',
                    'user'  => UserModel::getUser(Request::getInt('id'), 'id'),
                ]
            ]
        );
    }
}
