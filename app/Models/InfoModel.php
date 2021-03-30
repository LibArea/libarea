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
    
    // Голосование за посты
    public static function GrafVote()
    {
        $sql = "SELECT ROUND( comment_post_id, -1) AS bucket, COUNT(comment_votes), RPAD('', LN(COUNT(comment_votes)), '*') FROM comments GROUP BY bucket";
       
        return DB::run($sql)->fetchall(PDO::FETCH_BOTH); 
    }
    
    // Для графика
    // SELECT ROUND(votes_comm_item_id, -1) AS bucket, COUNT(votes_comm_points), RPAD('', LN(COUNT(votes_comm_points)), '*') FROM votes_comm GROUP BY bucket
}
