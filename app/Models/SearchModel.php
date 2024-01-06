<?php

namespace App\Models;

use App\Models\FacetModel;
use UserData;
use DB;

class SearchModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function getSearch($page, $limit, $query, $type)
    {
        if ($type == 'website') {
            return self::getWebsite($page, $limit, $query);
        }

        if ($type == 'comment') {
            return self::getComments($page, $limit, $query);
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
                            WHERE post_is_deleted = 0 AND post_draft = 0 AND post_tl = 0 AND post_hidden = 0 AND post_type = 'post'
                                AND MATCH(post_title, post_content) AGAINST (:qa)
                                          LIMIT :start, :limit";

        return DB::run($sql, ['qa' => $query, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getComments($page, $limit, $query)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT comment_id, comment_content, post_id, post_slug, post_title as title
                    FROM comments  
                    LEFT JOIN posts ON comment_post_id = post_id 
                        WHERE post_is_deleted = 0
                            AND comment_content LIKE :qa LIMIT :start, :limit";

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
            item_slug,
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
        if ($type == 'comment') {
            $sql = "SELECT comment_id FROM comments LEFT JOIN posts ON comment_post_id = post_id WHERE post_is_deleted = 0 AND comment_content LIKE :qa";

            return DB::run($sql, ['qa' => "%" . $query . "%"])->rowCount();
        }

        $sql = "SELECT post_id FROM posts WHERE post_is_deleted = 0 AND post_hidden = 0 AND post_draft = 0 AND post_tl = 0 AND post_type = 'post' AND MATCH(post_title, post_content) AGAINST (:qa)";

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

    // Find content for forms
    // Поиск контента для форм
    public static function getSelect($search, $type)
    {
        $field_id   = $type . '_id';
        if ($type == 'post') {
            $field_name = 'post_title';
            $sql = "SELECT post_id, post_title FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_hidden = 0 AND post_tl = 0 AND post_type = 'post' ORDER BY post_id DESC LIMIT 500";
        } elseif ($type == 'user') {
            $field_id = 'id';
            $field_name = 'login';
            $sql = "SELECT id, login, trust_level, activated FROM users WHERE activated = 1 AND login LIKE :login";
        } elseif ($type == 'team') {
            $field_id = 'id';
            $field_name = 'login';
            $sql = "SELECT id, login, trust_level, activated FROM users WHERE activated = 1 AND login LIKE :login AND id !=" . UserData::getUserId();
        } elseif ($type == 'poll') {
            $field_name = 'poll_title';
            $sql = "SELECT poll_id, poll_title FROM polls WHERE poll_title LIKE :poll_title AND poll_is_deleted = 0 AND poll_user_id = " . UserData::getUserId();

            if (UserData::checkAdmin()) {
                $sql = "SELECT poll_id, poll_title FROM polls WHERE poll_title LIKE :poll_title AND poll_is_deleted = 0";
            }
        } else {
            $condition = '';
            if (!UserData::checkAdmin()) {
                if ($type == 'blog') {

                    $blog = FacetModel::getFacetsUser('blog');
                    $teams = FacetModel::getTeamFacets('blog');

                    $resultUsers = [];
                    foreach (array_merge($teams, $blog) as $ind => $row) {
                        $resultUsers[$ind] = $row['facet_id'];
                    }

                    $condition =  "AND facet_id IN(" . implode(',', $resultUsers ?? []) . ")";
                }
            }

            $field_id = 'facet_id';
            $field_name = 'facet_title';
            $tl = UserData::getUserTl();
            $sql = "SELECT facet_id, facet_title, facet_type FROM facets 
                    WHERE facet_title LIKE :facet_title AND facet_tl <= $tl AND facet_is_deleted = 0 AND facet_type = '$type' $condition ORDER BY facet_count DESC LIMIT 200";
        }

        $lists = DB::run($sql, [$field_name => "%" . $search . "%"])->fetchAll();

        $response = [];
        foreach ($lists as $list) {
            $response[] = array(
                "id"    => $list[$field_id],
                "value" => $list[$field_name],
            );
        }

        return json_encode($response);
    }
}
