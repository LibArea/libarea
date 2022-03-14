<?php

namespace App\Models;

use DB;

class FavoriteModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Добавить / удалить из закладок
    public static function setFavorite($params)
    {
        $result = self::getUserFavorite($params);

        if (is_array($result)) {

            $sql = "DELETE FROM favorites WHERE tid = :tid AND user_id = :user_id AND action_type = :action_type";

            DB::run($sql, $params);

            self::delFavoriteTag($params);

            return 'del';
        }

        $sql = "INSERT INTO favorites(tid, user_id, action_type) VALUES(:tid, :user_id, :action_type)";

        DB::run($sql, $params);

        return 'add';
    }

    // Delete data from the link table for folders in bookmarks
    // Удалим данные из таблицы связи для папок в закладках
    public static function delFavoriteTag($params)
    { 
        $sql = "DELETE FROM folders_relation WHERE tid = :tid AND user_id = :user_id AND action_type = :action_type";

        return DB::run($sql, $params);
    }

    public static function getUserFavorite($params)
    {
        $sql = "SELECT tid, user_id, action_type FROM favorites 
                    WHERE tid = :tid AND user_id = :user_id AND action_type = :action_type";

        return  DB::run($sql, $params)->fetch();
    }
   
}
