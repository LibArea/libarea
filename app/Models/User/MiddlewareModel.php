<?php

namespace App\Models\User;

use DB;

class MiddlewareModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function getUser($id)
    {
        $sql = "SELECT 
                    id,
                    login,
                    limiting_mode,
                    scroll,
                    email,
                    avatar,
                    trust_level,
                    template,
                    lang,
                    invitation_available,
                    ban_list,
                    is_deleted 
                        FROM users WHERE id = :id";

        return DB::run($sql, ['id' => $id])->fetch();
    }
}
