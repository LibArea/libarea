<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class SearchModel extends \MainModel
{
    public static function getSearch($query)
    {
        $sql = "SELECT 
                    post_id, 
                    post_slug, 
                    post_space_id, 
                    post_title, 
                    post_content  
                        FROM posts WHERE post_content 
                        LIKE :qa1 OR post_title LIKE :qa2 ORDER BY post_id LIMIT 15";
        
        $result_q = DB::run($sql,['qa1' => "%".$query."%", 'qa2' => "%".$query."%"]);
        
        return $result_q->fetchall(PDO::FETCH_ASSOC);
    }

    // Для Sphinx 
    public static function getSearchServer($query)
    {
        $sql = "SELECT 
                    id as post_id, 
                    space_slug, 
                    space_name, 
                    space_img, 
                    post_slug, 
                    post_space_id, 
                    post_votes, 
                    SNIPPET(post_title, :qa) as _title, 
                    SNIPPET(post_content, :qa) AS _content 
                        FROM postind WHERE MATCH(:qa)";

        $result = DB::run($sql, ['qa' => $query], 'mysql.sphinx-search');
        return  $result->fetchall(PDO::FETCH_ASSOC);
    }
}