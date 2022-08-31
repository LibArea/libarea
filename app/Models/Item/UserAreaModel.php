<?php

namespace App\Models\Item;

use DB;

class UserAreaModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Sites added by the user
    // Сайты добавленные участником
    public static function getUserSites($page, $limit, $user_id)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT
                    item_id, 
                    item_title,
                    item_content,
                    item_published,
                    item_user_id,
                    item_url,
                    item_domain,
                    item_votes,
                    item_count,
                    item_date,
                    item_title_soft,
                    item_github_url,
                    item_following_link,
                    item_is_deleted,
                    rel.*
                        FROM items
                        LEFT JOIN
                        (
                            SELECT 
                                relation_item_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets  
                                LEFT JOIN facets_items_relation 
                                    on facet_id = relation_facet_id
                                        GROUP BY relation_item_id
                        ) AS rel
                            ON rel.relation_item_id = item_id 
                                WHERE item_user_id = :user_id AND item_is_deleted = 0
                                    ORDER BY item_id DESC LIMIT :start, :limit ";

        return DB::run($sql, ['user_id' => $user_id, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getUserSitesCount($user_id)
    {
        $sql = "SELECT item_id, item_is_deleted FROM items WHERE item_user_id = :user_id AND item_is_deleted = 0 ORDER BY item_id DESC";

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    // Bookmarks
    // Закладки
    public static function bookmarks($page, $limit, $user_id)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    fav.tid, 
                    fav.user_id, 
                    fav.action_type,
                    rel.*,
                    item_id,
                    item_url,
                    item_title,
                    item_content,
                    item_title_soft,
                    item_domain,
                    item_date,
                    item_github_url,
                    item_user_id,
                    item_votes,
                    item_following_link,
                    item_published,
                    item_is_deleted,
                    votes_item_user_id, votes_item_item_id
                        FROM favorites fav
                        LEFT JOIN
                        (
                            SELECT 
                                relation_item_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets  
                                LEFT JOIN facets_items_relation 
                                    on facet_id = relation_facet_id

                                        GROUP BY relation_item_id
                        ) AS rel
                            ON rel.relation_item_id = fav.tid
                            LEFT JOIN items ON item_id = fav.tid 
                            LEFT JOIN votes_item ON votes_item_item_id = fav.tid 
                                AND votes_item_user_id = :user_id
                                    WHERE fav.user_id = :uid_two AND fav.action_type = 'website' AND item_published = 1
                                    LIMIT :start, :limit";

        return  DB::run($sql, ['user_id' => $user_id, 'uid_two' => $user_id, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function bookmarksCount($user_id)
    {
        $sql = "SELECT user_id FROM favorites WHERE user_id = :user_id AND action_type = 'website'";

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    public static function auditCount()
    {
        return  DB::run("SELECT item_id FROM items WHERE item_published = 0")->rowCount();
    }
}
