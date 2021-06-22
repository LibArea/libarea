<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class ExploreModel extends \MainModel
{
    public static function getStats()
    {
        // $sql = "show table status";
        $sql  = "SELECT
                (SELECT COUNT(*) FROM answers) AS answer,
                (SELECT COUNT(*) FROM posts) AS post,
                (SELECT COUNT(*) FROM comments) AS comment,
                (SELECT COUNT(*) FROM users) AS user";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
   
    // График активности
    public static function getGraf()
    { 
        $sql = "SELECT COUNT(votes_answer_id), DATE(votes_answer_date) as date FROM votes_answer GROUP BY date limit 10";

        return DB::run($sql)->fetchall(PDO::FETCH_BOTH); 
    } 

    // Поcледний пост по ряду условий  
    public static function lastРost()
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                 ->where(['post_is_delete'], '=', 0)
                 ->and(['post_tl'], '=', 0)
                 ->and(['post_content_img'], '!=', '');
        
        return $query->orderBy(['post_id'])->desc()->getSelectOne();
    }  
    
    // Поcледний пост по ряду условий  
    public static function lastРostFive()
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                 ->where(['post_is_delete'], '=', 0)
                 ->and(['post_tl'], '=', 0)
                 ->and(['post_content_img'], '!=', '');
        
        return $query->orderBy(['post_id'])->desc()->limit(5)->getSelect();
     
    }  

    // Лучшие писатели  
    public static function bestTopUser()
    {
        return XD::select('*')->from(['users'])->where(['is_deleted'], '=', 0)
                    ->and(['avatar'], '!=', 'noavatar.png')
                    ->and(['id'], '!=', 1)
                    ->orderBy(['trust_level'])->desc()->limit(6)->getSelect();
    }  
 
}