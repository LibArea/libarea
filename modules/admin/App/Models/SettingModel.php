<?php

namespace Modules\Admin\App\Models;

use DB;

class SettingModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function get($group)
    {
        $sql = "SELECT name, entity, meaning FROM settings WHERE entity = :group";

        return DB::run($sql, ['group' => $group])->fetchAll();
    }
 
    public static function edit($params)
    {
        $sql = "UPDATE settings SET name = :name, meaning = :meaning WHERE name = :id";

        return  DB::run($sql, $params);
    }

}
