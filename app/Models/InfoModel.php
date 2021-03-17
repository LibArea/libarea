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

}
