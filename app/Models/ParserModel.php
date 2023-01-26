<?php

namespace App\Models;

use DB;

class ParserModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Member information (id, slug) for @nickname
    // Информация по участнику (id, slug) для @nickname
    public static function getUser($params, $type)
    {
        $field = ($type == 'slug') ? "login" : "id";

        $sql = "SELECT login FROM users WHERE $field = :params AND activated = 1 AND is_deleted = 0";

        return DB::run($sql, ['params' => $params])->fetch();
    }
}
