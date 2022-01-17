<?php

namespace App\Models\User;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class MiddlewareModel extends MainModel
{
    public static function getUser($id)
    {
        $sql = "SELECT 
                    id,
                    login,
                    limiting_mode,
                    email,
                    avatar,
                    trust_level,
                    template,
                    lang,
                    invitation_available,
                    ban_list,
                    is_deleted 
                        FROM users WHERE id = :id";

        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }
}
