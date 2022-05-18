<?php

namespace Modules\Admin\App\Models;

use DB;

class WordModel extends \Hleb\Scheme\App\Models\MainModel
{
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
