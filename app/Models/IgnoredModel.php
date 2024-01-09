<?php

namespace App\Models;

use DB;
use UserData;

class IgnoredModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function setIgnored(int $ignored_id)
    {
        if ($ignored_id == 0) {
            return false;
        }

        // We can't ignored ourselves
        if ($ignored_id == UserData::getUserId()) {
            return false;
        }

        $result = self::getUserIgnored($ignored_id);

        if (is_array($result)) {

            $sql = "DELETE FROM users_ignored WHERE ignored_id = :ignored_id AND user_id = :user_id";

            DB::run($sql, ['ignored_id' => $ignored_id, 'user_id' => UserData::getUserId()]);

            return 'del';
        }

        $sql = "INSERT INTO users_ignored(ignored_id, user_id) VALUES(:ignored_id, :user_id)";

        DB::run($sql, ['ignored_id' => $ignored_id, 'user_id' => UserData::getUserId()]);

        return 'add';
    }

    public static function getUserIgnored(int $ignored_id)
    {
        $sql = "SELECT ignored_id, user_id FROM users_ignored WHERE ignored_id = :ignored_id AND user_id = :user_id";

        return  DB::run($sql, ['ignored_id' => $ignored_id, 'user_id' => UserData::getUserId()])->fetch();
    }

    public static function getIgnoredUsers(int $limit)
    {
        $sql = "SELECT i.ignored_id,
                       u.login,
                       u.avatar
                          FROM users_ignored i 
                              LEFT JOIN users u ON u.id = i.ignored_id
                                  WHERE i.user_id = :user_id ORDER BY i.id DESC LIMIT :limit";

        return  DB::run($sql, ['limit' => $limit, 'user_id' => UserData::getUserId()])->fetchAll();
    }
}
