<?php

namespace Modules\Catalog\App\Models;

use DB;

class UserAreaModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Sites added by the user
    // Сайты добавленные участником
    public static function getUserSites($page, $limit, $user)
    {  
        $start  = ($page - 1) * $limit;
        $sql = "SELECT
                    item_id, 
                    item_title_url,
                    item_content_url,
                    item_published,
                    item_user_id,
                    item_url,
                    item_url_domain,
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
                                    LIMIT $start, $limit ";

        return DB::run($sql, ['uid' => $user['id']])->fetchAll();
    }
    
    public static function getUserSitesCount($user)
    {  
        $sql = "SELECT item_id, item_is_deleted FROM items WHERE item_user_id = :uid ORDER BY item_id DESC";

        return  DB::run($sql, ['uid' => $user['id']])->rowCount();
    }

    // Bookmarks
    // Закладки
    public static function bookmarks($page, $limit, $uid)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    favorite_tid, 
                    favorite_user_id, 
                    favorite_type,
                    rel.*,
                    item_id,
                    item_url,
                    item_title_url,
                    item_content_url,
                    item_title_soft,
                    item_url_domain,
                    item_github_url,
                    item_user_id,
                    item_votes,
                    item_following_link,
                    item_published,
                    votes_item_user_id, votes_item_item_id
                        FROM favorites
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
                            ON rel.relation_item_id = favorite_tid
                            LEFT JOIN items ON item_id = favorite_tid 
                            LEFT JOIN votes_item ON votes_item_item_id = favorite_tid 
                                AND votes_item_user_id = :uid
                                    WHERE favorite_user_id = :uid_two AND favorite_type = 3 AND item_published = 1
                                    LIMIT $start, $limit";

        return  DB::run($sql, ['uid' => $uid, 'uid_two' => $uid])->fetchAll();
    }

    public static function bookmarksCount($uid)
    {
        $sql = "SELECT favorite_user_id FROM favorites WHERE favorite_user_id = :uid AND favorite_type = 3";

        return  DB::run($sql, ['uid' => $uid])->rowCount();
    }
}
