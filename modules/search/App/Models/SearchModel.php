<?php

namespace Modules\Search\App\Models;

use DB;

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
                            WHERE  post_is_deleted = 0 and post_draft = 0 and post_tl = 0 
                                AND MATCH(post_title, post_content) AGAINST (:qa)
                                          LIMIT $start, $limit";
        return DB::run($sql, ['qa' => $query])->fetchall();
    }

    public static function getSearchCount($query)
    {
        $sql = "SELECT DISTINCT 
                  post_id
                    FROM posts
                            WHERE post_is_deleted = 0 and post_draft = 0 and post_tl = 0 
                             AND MATCH(post_title, post_content) AGAINST (:qa)";

        return DB::run($sql, ['qa' => "%" . $query . "%"])->rowCount();
    }

    // For Sphinx 
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

        return DB::run($sql, ['qa' => $query], 'mysql.sphinx-search')->fetchall();
    }

    public static function getSearchTags($query, $type, $limit)
    {
        if ($type == 'server') {

            $sql = "SELECT 
                facet_slug, 
                facet_count, 
                facet_title,
                facet_type,
                facet_img
                    FROM tagind WHERE facet_type = 'topic' AND MATCH(:qa) LIMIT $limit";

            return DB::run($sql, ['qa' => $query], 'mysql.sphinx-search')->fetchall();
        }

        $sql = "SELECT 
                    facet_slug, 
                    facet_count, 
                    facet_title,
                    facet_img
                        FROM facets WHERE facet_type = 'topic' AND facet_title LIKE :qa1 OR facet_slug LIKE :qa2 
                            LIMIT $limit";

        return DB::run($sql, ['qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%"])->fetchAll();
    }

    public static function setSearchLogs($params)
    {
        $sql = "INSERT INTO search_logs(request, 
                            action_type, 
                            add_ip,
                            user_id, 
                            count_results) 
                               VALUES(:request, 
                                   :action_type, 
                                   :add_ip,
                                   :user_id, 
                                   :count_results)";

        DB::run($sql, $params);
    }

    public static function getSearchLogs($limit)
    {
        $sql = "SELECT 
                    request, 
                    action_type,
                    add_date,
                    add_ip,
                    user_id, 
                    count_results
                        FROM search_logs ORDER BY id DESC LIMIT $limit";

        return DB::run($sql)->fetchAll();
    }
}
