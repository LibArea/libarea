<?php

namespace App\Models;

use DB;

class PageModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Информация по странице  (id, slug)
    public static function getPage($params, $uid, $name)
    {
        $sort = "post_id = :params";
        if ($name == 'slug') $sort = "post_slug = :params";

        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_date,
                    post_votes,
                    post_modified,
                    post_content,
                    post_user_id,
                    post_ip,
                    post_is_deleted,
                    votes_post_item_id, 
                    votes_post_user_id,
                    id,
                    login,
                    avatar
                        FROM posts 
                        LEFT JOIN users ON id = post_user_id
                        LEFT JOIN votes_post ON votes_post_item_id = post_id 
                                AND votes_post_user_id = :uid
                            WHERE $sort";

        $result = DB::run($sql, ['params' => $params, 'uid' =>  $uid]);

        return $result->fetch();
    }


    // Последние 5 страниц по фасету
    public static function recentPosts($facet_id, $post_id)
    {
        $and = '';
        if ($post_id > 0) $and = 'AND post_id != ' . $post_id;

        $sql = "SELECT 
                    post_id,
                    post_slug,
                    post_title
                        FROM facets_posts_relation 
                            LEFT JOIN posts on post_id = relation_post_id
                                WHERE relation_facet_id = :facet_id AND post_type = 'page'
                                    $and
                                    ORDER BY post_id DESC LIMIT 5";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Последние 5 страниц по фасету
    public static function recentPostsAll()
    {
        $sql = "SELECT 
                    post_id,
                    post_slug,
                    post_type,
                    post_title,
                    post_is_deleted
                        FROM facets_posts_relation 
                            LEFT JOIN posts on post_id = relation_post_id
                                WHERE post_type = 'page'
                                    ORDER BY post_id DESC LIMIT 25";

        return DB::run($sql)->fetchAll();
    }
}
