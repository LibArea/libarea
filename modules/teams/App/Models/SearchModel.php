<?php

namespace Modules\Teams\App\Models;

use DB;

class SearchModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Find members for a team
    // Поиск участников для команд
    public static function get($search, $uid)
    {
        $sql = "SELECT id, login, activated FROM users WHERE activated = 1 AND login LIKE :login AND id != :id";

        $lists = DB::run($sql, ['id' => $uid, 'login' => "%" . $search . "%"])->fetchAll();

        $response = [];
        foreach ($lists as $list) {
            $response[] = array(
                "id"    => $list['id'],
                "value" => $list['login'],
            );
        }

        return json_encode($response);
    }
}
