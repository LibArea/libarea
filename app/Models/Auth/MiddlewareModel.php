<?php

declare(strict_types=1);

namespace App\Models\Auth;

use Hleb\Base\Model;
use Hleb\Static\DB;

class MiddlewareModel extends Model
{
    public static function getUser(int $id)
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
                    nsfw,
					post_design,
                    invitation_available,
                    ban_list,
                    is_deleted
                        FROM users
                                WHERE id = :id";

        return DB::run($sql, ['id' => $id])->fetch();
    }
    
    public static function getBlog(int $id)
    {
        return DB::run("SELECT facet_slug FROM facets WHERE facet_user_id = :id AND facet_type = 'blog'", ['id' => $id])->fetch();
    }
}
