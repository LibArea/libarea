<?php

namespace App\Models;

use DB;

class ContentModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Member information (id, slug) 
    // Информация по участнику (id, slug)
    public static function getUsers($params, $name)
    {
        $sort = "id = :params";
        if ($name == 'slug') {
            $sort = "login = :params";
        }

        $sql = "SELECT 
                    id,
                    login,
                    activated,
                    is_deleted 
                        FROM users WHERE $sort AND activated = 1 AND is_deleted = 0";

        $result = DB::run($sql, ['params' => $params]);

        return $result->fetch();
    }

    // Member Content Posting Frequency 
    // Частота размещения контента участника 
    public static function getSpeed($uid, $type)
    {
        $sql = "SELECT 
                    " . $type . "_id, 
                    " . $type . "_user_id, 
                    " . $type . "_date
                    FROM " . $type . "s 
                        WHERE " . $type . "_user_id = :uid
                        AND " . $type . "_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['uid' => $uid])->rowCount();
    }

    // Get a list of forbidden stop words
    // Получим список запрещенных стоп-слов
    public static function getStopWords()
    {
        $sql = "SELECT stop_id, stop_word FROM stop_words";

        return DB::run($sql)->fetchAll();
    }

    // Add a safe word
    // Добавим стоп-слово
    public static function setStopWord($params)
    {
        $sql = "INSERT INTO stop_words(stop_word, stop_add_uid, stop_space_id) 
                    VALUES(:stop_word, :stop_add_uid, :stop_space_id)";

        return DB::run($sql, $params);
    }

    // Delete the safe word
    // Удалим стоп-слово
    public static function deleteStopWord($word_id)
    {
        $sql = "DELETE FROM stop_words WHERE stop_id = :word_id";

        return DB::run($sql, ['word_id' => $word_id]);
    }
}
