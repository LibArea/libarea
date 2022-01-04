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
                    user_id,
                    user_login,
                    user_limiting_mode,
                    user_email,
                    user_avatar,
                    user_trust_level,
                    user_template,
                    user_lang,
                    user_invitation_available,
                    user_ban_list,
                    user_is_deleted 
                        FROM users WHERE user_id = :id";

        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }
   
}
