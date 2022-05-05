<?php

namespace Modules\Catalog\App\Models;

use DB;

class UserAreaModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Sites added by the user
    // Сайты добавленные участником
    public static function getUserSites($page, $limit, $uid)
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
                                WHERE item_user_id = :uid ORDER BY item_id DESC
                                    LIMIT :start, :limit ";

        return DB::run($sql, ['uid' => $uid, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getUserSitesCount($uid)
    {
        $sql = "SELECT item_id, item_is_deleted FROM items WHERE item_user_id = :uid ORDER BY item_id DESC";

        return  DB::run($sql, ['uid' => $uid])->rowCount();
    }

    // Bookmarks
    // Закладки
    public static function bookmarks($page, $limit, $uid)
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
                                AND votes_item_user_id = :uid
                                    WHERE fav.user_id = :uid_two AND fav.action_type = 'website' AND item_published = 1
                                    LIMIT :start, :limit";

        return  DB::run($sql, ['uid' => $uid, 'uid_two' => $uid, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function bookmarksCount($uid)
    {
        $sql = "SELECT user_id FROM favorites WHERE user_id = :uid AND action_type = 'website'";

        return  DB::run($sql, ['uid' => $uid])->rowCount();
    }
    
    public static function auditCount()
    {
        return  DB::run("SELECT item_id FROM items WHERE item_published = 0")->rowCount();
    }
}
