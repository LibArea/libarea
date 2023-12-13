<?php

namespace App\Models\User;

use UserData;
use DB;

class DeviceIDModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function get()
    {
        $device = DB::run("SELECT device_id FROM users_agent_logs WHERE user_id = ? ORDER BY id DESC", [UserData::getUserId()])->fetch();
		
		return $device['device_id'];
    }
	
    public static function create($device_id)
    {
		$user_id = UserData::getUserId();
 
		// Обновляем только последнюю строку в таблице с данным user_id. Мы создаем временную таблицу, т.к. 
		// MySQL не позволяет одновременно выбирать из таблицы и обновлять ее. Но мы обойдет это дело. :)
		// We update only the last row in the table with the given user_id. We are creating a temporary table, because 
		// MySQL doesn't allow selecting from a table and update in the same table at the same time. But there is always a workaround :)
		$sql = "UPDATE users_agent_logs SET device_id = :device_id WHERE id = (SELECT MAX(id) FROM (SELECT *  FROM users_agent_logs) AS device_new) AND user_id = $user_id";
		
		return DB::run($sql, ['device_id' => $device_id]);
    }
}

