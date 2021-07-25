<?php

namespace App\Models;
use DB;
use PDO;

class ContentModel extends \MainModel
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
        
        return DB::run($sql,$params);
    }
    
    // Удалить стоп-слово
    public static function deleteStopWord($word_id)
    {
        $sql = "DELETE FROM stop_words WHERE stop_id = :word_id";
                    
        return DB::run($sql, ['word_id' => $word_id]);
    } 
    
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
        
        return $result->fetch(PDO::FETCH_ASSOC);     
    }
   
}
