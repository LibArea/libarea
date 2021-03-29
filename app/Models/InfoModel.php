<?php

namespace App\Models;
use XdORM\XD;
use DB;

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
    
    // Для графика
    // SELECT ROUND(votes_comm_item_id, -1) AS bucket, COUNT(votes_comm_item_id), RPAD('', LN(COUNT(votes_comm_item_id)), '*') FROM ( SELECT votes_comm_item_id, COUNT(votes_comm_id) AS cnt FROM votes_comm WHERE votes_comm_item_id IS NOT NULL GROUP BY votes_comm_item_id ) AS counted GROUP BY bucket
}
