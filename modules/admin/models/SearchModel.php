<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class SearchModel extends Model
{
    public static function getSearchLogs($limit)
    {
        $sql = "SELECT 
                    request, 
                    action_type,
                    add_date,
                    add_ip,
                    user_id, 
                    count_results
                        FROM search_logs ORDER BY id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }
	
	public static function getSearchUsers(int $page, int $limit, null|string $login = '', null|string $ip = '')
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT id, login, name, reg_ip, email, trust_level FROM users WHERE login LIKE :login AND reg_ip LIKE :ip LIMIT :start, :limit";

        return DB::run($sql, ['login' => "%" . $login . "%", 'ip' => $ip . "%", 'start' => $start, 'limit' => $limit])->fetchAll();
    }
}
