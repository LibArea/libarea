<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class InfoModel extends \MainModel
{
    
    // Всего участников
    public static function getUsersNumAll()
    {
        $query = XD::select('*')->from(['users']);

        return count($query->getSelect());
    }
    
   // Количество постов
    public static function getPostsNumAll()
    {
        $query = XD::select('*')->from(['posts']);
       
        return count($query->getSelect());
    } 
    
    // Количество комментариев
    public static function getCommentsNumAll()
    {
        $query = XD::select('*')->from(['comments']);
       
        return count($query->getSelect());
    }

    // Голосование за комментарии
    public static function getCommentsVoteNumAll()
    {
        $query = XD::select('*')->from(['votes_comm']);
       
        return count($query->getSelect());
    }
    
    // Голосование за посты
    public static function getPostVoteNumAll()
    {
        $query = XD::select('*')->from(['votes_post']);
       
        return count($query->getSelect());
    }
    
    // Группировка событий
    public static function GrafFlow()
    { 
        $sql = "SELECT count(flow_id), DATE(flow_pubdate) date FROM flow_log GROUP BY date";
        
        return DB::run($sql)->fetchall(PDO::FETCH_BOTH); 
    } 
 
}
