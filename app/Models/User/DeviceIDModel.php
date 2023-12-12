<?php

namespace App\Models\User;

use UserData;
use DB;

class DeviceIDModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function create($device_id)
    {
        return DB::run("UPDATE users SET device_id = :device_id WHERE id = :user_id", ['device_id' => $device_id, 'user_id' => UserData::getUserId()]);
    }
}
