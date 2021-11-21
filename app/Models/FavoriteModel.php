<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class FavoriteModel extends MainModel
{
    // Добавить / удалить из закладок
    public static function setFavorite($content_id, $user_id, $type)
    {
        $result = self::getUserFavorite($content_id, $user_id, $type);

        if (is_array($result)) {

            $sql = "DELETE FROM favorites WHERE favorite_tid =  :content_id AND favorite_user_id = :user_id";

            DB::run($sql, ['content_id' => $content_id, 'user_id' => $user_id]);

            return 'del';
        }

        $params = [
            'favorite_tid'      => $content_id,
            'favorite_user_id'  => $user_id,
            'favorite_type'     => $type,
        ];

        $sql = "INSERT INTO favorites(favorite_tid, favorite_user_id, favorite_type) 
                       VALUES(:favorite_tid, :favorite_user_id, :favorite_type)";

        DB::run($sql, $params);

        return 'add';
    }

    public static function getUserFavorite($content_id, $user_id, $type)
    {
        $sql = "SELECT favorite_tid, favorite_user_id, favorite_type
                    FROM favorites 
                        WHERE favorite_tid = :content_id AND favorite_user_id = :user_id AND favorite_type = :type";

        return  DB::run($sql, ['content_id' => $content_id, 'user_id' => $user_id, 'type' => $type])->fetch(PDO::FETCH_ASSOC);
    }
}
