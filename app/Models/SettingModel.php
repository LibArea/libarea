<?php

namespace App\Models;

use DB;

class SettingModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function get()
    {
        return  DB::run("SELECT val, value FROM settings")->fetchAll();
    }
    
    public static function change($val, $value)
    {
      return DB::run("UPDATE settings SET value = ? WHERE val = ?", [$value, $val]);
    }
}
