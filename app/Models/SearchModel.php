<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class SearchModel extends MainModel
{
    public static function getSearch($query, $limit)
    {
        $sql = "SELECT DISTINCT 
                post_id, post_title as title, post_slug, post_type, post_translation, 
                post_draft, post_date, post_published, post_user_id, post_votes, 
                post_answers_count, post_comments_count, post_content as content, post_content_img, 
                post_thumb_img, post_merged_id, post_closed, post_tl, post_lo, post_top,  
                post_url_domain, post_is_deleted, post_hits_count, 
                rel.*,  
                user_id, user_login, user_avatar 
            FROM topics_post_relation  
            LEFT JOIN posts ON relation_post_id = post_id 
            LEFT JOIN ( SELECT  
                    MAX(topic_id),  
                    MAX(topic_slug),  
                    MAX(topic_title),
                    MAX(relation_topic_id),  
                    MAX(relation_post_id) as p_id,  
                    GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list  
                    FROM topics  
                    LEFT JOIN topics_post_relation on topic_id = relation_topic_id  
                        GROUP BY relation_post_id  
            ) AS rel ON rel.p_id = post_id  
                LEFT JOIN users ON user_id = post_user_id 
                WHERE post_is_deleted = 0 and post_draft = 0 and post_tl = 0 
                     AND post_content LIKE :qa1 
                     OR post_title LIKE :qa2 ORDER BY post_id LIMIT $limit";

        return DB::run($sql, ['qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%"])->fetchall(PDO::FETCH_ASSOC);
    }

    // Для Sphinx 
    public static function getSearchPostServer($query, $limit)
    {
        $sql = "SELECT 
                    id AS post_id, 
                    post_slug, 
                    post_votes, 
                    post_hits_count,
                    topic_list,
                    user_login,
                    user_avatar,
                    SNIPPET(post_title, :qa) AS title, 
                    SNIPPET(post_content, :qa) AS content 
                        FROM postind WHERE MATCH(:qa) LIMIT $limit"; 

        return DB::run($sql, ['qa' => $query], 'mysql.sphinx-search')->fetchall(PDO::FETCH_ASSOC);
    }
    
    public static function getSearchTags($query, $type)
    {
        if ($type == 'server') {

            $sql = "SELECT 
                topic_slug, 
                topic_count, 
                topic_title,
                topic_img
                    FROM tagind WHERE MATCH(:qa) LIMIT 10";
                    
            return DB::run($sql, ['qa' => $query], 'mysql.sphinx-search')->fetchall(PDO::FETCH_ASSOC);
        } 
        
        $sql = "SELECT 
                    topic_slug, 
                    topic_count, 
                    topic_title,
                    topic_img
                        FROM topics WHERE topic_title LIKE :qa OR topic_slug LIKE :qa LIMIT 10";

        return DB::run($sql, ['qa' => "%" . $query . "%"])->fetchall(PDO::FETCH_ASSOC);
    }
    
}
