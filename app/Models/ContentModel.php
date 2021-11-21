<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class ContentModel extends MainModel
{
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

    // Частота размещения контента участника 
    public static function getSpeed($user_id, $type)
    {
        $sql = "SELECT 
                    " . $type . "_id, 
                    " . $type . "_user_id, 
                    " . $type . "_date
                    FROM " . $type . "s 
                        WHERE " . $type . "_user_id = :user_id
                        AND " . $type . "_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

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

    // Добавить стоп-слово
    public static function setStopWord($data)
    {
        $params = [
            'stop_word'     => $data['stop_word'],
            'stop_add_uid'  => $data['stop_add_uid'],
            'stop_space_id' => $data['stop_space_id'],
        ];

        $sql = "INSERT INTO stop_words(stop_word, stop_add_uid, stop_space_id) 
                    VALUES(:stop_word, :stop_add_uid, :stop_space_id)";

        return DB::run($sql, $params);
    }

    // Удалить стоп-слово
    public static function deleteStopWord($word_id)
    {
        $sql = "DELETE FROM stop_words WHERE stop_id = :word_id";

        return DB::run($sql, ['word_id' => $word_id]);
    }
}
