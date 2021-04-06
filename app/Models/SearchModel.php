<?php

namespace App\Models;
use DB;
use PDO;

class SearchModel extends \MainModel
{
    public static function getSearch($query)
    {
        $sql = "SELECT post_id, post_slug, post_title, post_content  FROM posts
                WHERE post_content LIKE :qa1 OR post_title LIKE :qa2 ORDER BY post_id LIMIT 10";
        $result_q = DB::run($sql,['qa1' => "%".$query."%", 'qa2' => "%".$query."%"]);
        $result = $result_q->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }

}