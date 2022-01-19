<?php

namespace Modules\Search;

use DB;
use PDO;

class SearchModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function getSearch($page, $limit, $query)
    {
      $start  = ($page - 1) * $limit;  
        
      $sql = "SELECT DISTINCT 
                post_id, 
                post_title as title, 
                post_slug, 
                post_feature, 
                post_translation, 
                post_draft, 
                post_date, 
                post_published, 
                post_user_id, 
                post_votes, 
                post_answers_count, 
                post_comments_count, 
                post_content as content,
                post_content_img, 
                post_thumb_img, 
                post_merged_id, 
                post_closed, 
                post_tl, 
                post_lo, 
                post_top,  
                post_url_domain, 
                post_is_deleted, 
                post_hits_count, 
                rel.*,  
                id, login, avatar 
                    FROM facets_posts_relation  
                    LEFT JOIN posts ON relation_post_id = post_id 
                    LEFT JOIN ( SELECT  
                            relation_post_id,  
                            GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list  
                            FROM facets  
                            LEFT JOIN facets_posts_relation on facet_id = relation_facet_id  
                                GROUP BY relation_post_id  
                    ) AS rel ON rel.relation_post_id = post_id  
                        LEFT JOIN users ON id = post_user_id 
                            WHERE post_is_deleted = 0 and post_draft = 0 and post_tl = 0 
                             AND post_content LIKE :qa1 
                             OR post_title LIKE :qa2 ORDER BY post_id LIMIT $start, $limit";

        return DB::run($sql, ['qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%"])->fetchall(PDO::FETCH_ASSOC);

    }

    public static function getSearchCount($query)
    {
      $sql = "SELECT DISTINCT 
                post_id, 
                post_title, 
                post_slug, 
                post_feature, 
                post_translation, 
                post_draft, 
                post_date, 
                post_published, 
                post_content,
                post_content_img, 
                post_thumb_img, 
                post_merged_id, 
                post_closed, 
                post_tl, 
                post_lo 
                    FROM posts

                            WHERE post_is_deleted = 0 and post_draft = 0 and post_tl = 0 
                             AND post_content LIKE :qa1 
                             OR post_title LIKE :qa2";

        return DB::run($sql, ['qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%"])->rowCount();
    }

    // Для Sphinx 
    public static function getSearchPostServer($query, $limit)
    {
        $sql = "SELECT 
                    id AS post_id, 
                    post_slug, 
                    post_votes, 
                    post_hits_count,
                    facet_list,
                    login,
                    avatar,
                    SNIPPET(post_title, :qa) AS title, 
                    SNIPPET(post_content, :qa) AS content 
                        FROM postind WHERE MATCH(:qa) LIMIT $limit";

        return DB::run($sql, ['qa' => $query], 'mysql.sphinx-search')->fetchall(PDO::FETCH_ASSOC);
    }

    public static function getSearchTags($query, $type, $limit)
    {
        if ($type == 'server') {

            $sql = "SELECT 
                facet_slug, 
                facet_count, 
                facet_title,
                facet_img
                    FROM tagind WHERE MATCH(:qa) LIMIT $limit";

            return DB::run($sql, ['qa' => $query], 'mysql.sphinx-search')->fetchall(PDO::FETCH_ASSOC);
        }

        $sql = "SELECT 
                    facet_slug, 
                    facet_count, 
                    facet_title,
                    facet_img
                        FROM facets WHERE facet_title LIKE :qa OR facet_slug LIKE :qa LIMIT $limit";

        return DB::run($sql, ['qa' => "%" . $query . "%"])->fetchall(PDO::FETCH_ASSOC);
    }
}
