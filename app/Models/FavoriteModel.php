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

            $sql = "DELETE FROM favorites 
                        WHERE favorite_tid = :favorite_tid 
                            AND favorite_user_id = :favorite_user_id 
                            AND favorite_type = :favorite_type";

            DB::run($sql, $params);

            return 'del';
        }

        $sql = "INSERT INTO favorites(favorite_tid, favorite_user_id, favorite_type) 
                       VALUES(:favorite_tid, :favorite_user_id, :favorite_type)";

        DB::run($sql, $params);

        return 'add';
    }

    public static function getUserFavorite($params)
    {
        $sql = "SELECT favorite_tid, favorite_user_id, favorite_type
                    FROM favorites 
                        WHERE favorite_tid = :favorite_tid AND favorite_user_id = :favorite_user_id AND favorite_type = :favorite_type";

        return  DB::run($sql, $params)->fetch();
    }
}
