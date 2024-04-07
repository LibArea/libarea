<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class SettingModel extends Model
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
