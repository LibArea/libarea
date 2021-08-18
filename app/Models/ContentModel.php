<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class ContentModel extends MainModel
{
    // Получим список запрещенных стоп-слов
    public static function getStopWords()
    {
        $sql = "SELECT 
                    stop_id, 
                    stop_word, 
                    stop_add_uid, 
                    stop_space_id, 
                    stop_date
                        FROM stop_words";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Информация по участнику (id, slug)
    public static function getUsers($params, $name)
    {
        $sort = "user_id = :params";
        if ($name == 'slug') {
            $sort = "user_login = :params";
        }

        $sql = "SELECT 
                    user_id,
                    user_login,
                    user_activated,
                    user_is_deleted 
                        FROM users WHERE $sort AND user_activated = 1 AND user_is_deleted = 0";

        $result = DB::run($sql, ['params' => $params]);

        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
