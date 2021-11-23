<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\AuthModel;

class BanController extends MainController
{
    public static function getBan($user)
    {
        if ($user['user_ban_list'] == 1) {
            if (!isset($_SESSION)) {
                session_start();
            }
            session_destroy();
            AuthModel::deleteTokenByUserId($user['user_id']);
            redirect('/info/restriction');
        }
    }
}
