<?php

namespace App\Models;
use DB;
use PDO;

class InfoModel extends \MainModel
{
    public static function getStatsAll()
    {
        // $sql = "show table status";
        $sql  = "SELECT
                (SELECT COUNT(*) FROM answers) AS answer,
                (SELECT COUNT(*) FROM posts) AS post,
                (SELECT COUNT(*) FROM comments) AS comment,
                (SELECT COUNT(*) FROM users) AS user";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
   
    public static function GrafAnsw()
    { 
        $sql = "SELECT COUNT(votes_answ_id), DATE(votes_answ_date) as date FROM votes_answ GROUP BY date limit 10";

        return DB::run($sql)->fetchall(PDO::FETCH_BOTH); 
    } 
 
}
