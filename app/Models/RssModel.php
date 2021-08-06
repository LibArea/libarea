<?php

namespace App\Models;

use DB;
use PDO;

class RssModel extends \MainModel
{
    // Все посты для Sitemap
    public static function getPostsSitemap()
    {
        $sql = "SELECT post_id, post_slug, post_tl, post_is_deleted
                    FROM posts 
                        WHERE post_is_deleted != 1 AND post_tl = 0";

        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Все пространства для Sitemap
    public static function getSpacesSitemap()
    {
        $sql = "SELECT space_slug, space_is_delete
                    FROM spaces 
                        WHERE space_is_delete != 1";

        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Посты по id пространства для rss
    public static function getPostsFeed($space_id)
    {
        $sql = "SELECT 
                    post_id, 
                    post_title, 
                    post_content, 
                    post_slug, 
                    post_date, 
                    post_space_id, 
                    post_tl, 
                    post_content_img, 
                    post_is_deleted
                        FROM posts 
                            WHERE post_space_id = :space_id AND post_is_deleted != 1 AND post_tl = 0 AND post_content_img != '' 
                            ORDER BY post_id DESC";

        return  DB::run($sql, ['space_id' => $space_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Информация по самому пространству для rss поста
    public static function getSpaceId($space_id)
    {
        $sql = "SELECT space_id, space_name, space_slug, space_short_text, space_is_delete
                    FROM spaces 
                        WHERE space_id = :space_id AND space_is_delete != 1";

        return  DB::run($sql, ['space_id' => $space_id])->fetch(PDO::FETCH_ASSOC);
    }
}
