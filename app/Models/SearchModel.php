<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class SearchModel extends MainModel
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

        return DB::run($sql, ['qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%"])->fetchall(PDO::FETCH_ASSOC);
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

        return DB::run($sql, ['qa' => $query], 'mysql.sphinx-search')->fetchall(PDO::FETCH_ASSOC);
    }
}
