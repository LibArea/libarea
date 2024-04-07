<?php

declare(strict_types=1);

namespace App\Models\User;

use Hleb\Base\Model;
use Hleb\Static\DB;

class DeviceIDModel extends Model
{
    public static function get()
    {
        return DB::run("SELECT device_id FROM users_agent_logs WHERE user_id = ? ORDER BY id DESC", [self::container()->user()->id()])->fetch();
    }
	
    public static function create(int $device_id): \PDOStatement
    {
		$user_id = self::container()->user()->id();
 
		// Обновляем только последнюю строку в таблице с данным user_id. Мы создаем временную таблицу, т.к. 
		// MySQL не позволяет одновременно выбирать из таблицы и обновлять ее. )
		// We update only the last row in the table with the given user_id. We are creating a temporary table, because 
		// MySQL doesn't allow selecting from a table and update in the same table at the same time. )
		$sql = "UPDATE users_agent_logs SET device_id = :device_id WHERE id = (SELECT MAX(id) FROM (SELECT *  FROM users_agent_logs) AS device_new) AND user_id = $user_id";
		
		return DB::run($sql, ['device_id' => $device_id]);
    }
}

