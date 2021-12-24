<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class StatsModel extends MainModel
{
    // Страница аудита
    public static function getCount()
    {
        $sql = "SELECT 
                    (SELECT COUNT(post_id) 
                        FROM posts WHERE post_type = 'post') 
                            AS count_posts,
                            
                    (SELECT COUNT(post_id) 
                        FROM posts WHERE post_type = 'page') 
                            AS count_pages,        
                  
                    (SELECT COUNT(answer_id) 
                        FROM answers ) 
                            AS count_answers,
                  
                    (SELECT COUNT(comment_id) 
                        FROM comments ) 
                            AS count_comments,
                     
(                   SELECT COUNT(facet_id) 
                        FROM facets WHERE facet_type = 'blog') 
                            AS count_blogs";

        return DB::run($sql)->fetch(PDO::FETCH_ASSOC);
    }
}
