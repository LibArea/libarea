<?php

namespace App\Models;

use DB;

class SearchModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function getSearch($page, $limit, $query, $type)
    {
        if ($type == 'website') {
            return self::getWebsite($page, $limit, $query);
        }

        if ($type == 'answer') {
            return self::getAnswers($page, $limit, $query);
        }

        return self::getPosts($page, $limit, $query);
    }

    public static function getPosts($page, $limit, $query)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT DISTINCT 
                post_id, 
                post_title as title, 
                post_slug, 
                post_published, 
                post_user_id, 
                post_votes as votes, 
                post_content as content,
                post_hits_count as count, 
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
                            WHERE post_is_deleted = 0 and post_draft = 0 and post_tl = 0 and post_type = 'post'
                                AND MATCH(post_title, post_content) AGAINST (:qa)
                                          LIMIT :start, :limit";

        return DB::run($sql, ['qa' => $query, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getAnswers($page, $limit, $query)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT answer_id, answer_content, post_id, post_slug, post_title as title
                    FROM answers  
                    LEFT JOIN posts ON answer_post_id = post_id 
                        WHERE post_is_deleted = 0
                            AND answer_content LIKE :qa LIMIT :start, :limit";

        return DB::run($sql, ['qa' => "%" . $query . "%", 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getWebsite($page, $limit, $query)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT DISTINCT 
            item_id, 
            item_title as title, 
            item_content as content,
            item_url,
            item_domain,
            item_votes as votes,
            item_count as count,
            rel.*
                FROM facets_items_relation  
                LEFT JOIN items ON relation_item_id = item_id 
                LEFT JOIN ( SELECT  
                        relation_item_id,  
                        GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list  
                        FROM facets  
                        LEFT JOIN facets_items_relation on facet_id = relation_facet_id  
                            GROUP BY relation_item_id  
                ) AS rel ON rel.relation_item_id = item_id  
                        WHERE item_is_deleted = 0
                            AND MATCH(item_title, item_content, item_domain) AGAINST (:qa) LIMIT :start, :limit";

        return DB::run($sql, ['qa' => $query, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getSearchCount($query, $type)
    {
        if ($type == 'answer') {
            $sql = "SELECT answer_id FROM answers LEFT JOIN posts ON answer_post_id = post_id WHERE post_is_deleted = 0 AND answer_content LIKE :qa";

            return DB::run($sql, ['qa' => "%" . $query . "%"])->rowCount();
        }

        $sql = "SELECT post_id FROM posts WHERE post_is_deleted = 0 and post_draft = 0 and post_tl = 0 and post_type = 'post' AND MATCH(post_title, post_content) AGAINST (:qa)";

        if ($type == 'website') {
            $sql = "SELECT item_id FROM items WHERE item_is_deleted = 0 AND MATCH(item_title, item_content, item_domain) AGAINST (:qa)";
        }

        return DB::run($sql, ['qa' => $query])->rowCount();
    }

    public static function getSearchTags($query, $type, $limit)
    {
        $sql = "SELECT 
                    facet_slug, 
                    facet_count, 
                    facet_title,
                    facet_type,
                    facet_img
                        FROM facets WHERE facet_type = :type AND (facet_title LIKE :qa1 OR facet_slug LIKE :qa2)
                            LIMIT :limit";

        return DB::run($sql, ['type' => $type, 'qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%", 'limit' => $limit])->fetchAll();
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
                        FROM search_logs ORDER BY id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }
}
