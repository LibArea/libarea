<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class RssModel extends MainModel
{
    // Все посты для Sitemap
    public static function getPostsSitemap()
    {
        $sql = "SELECT post_id, post_slug, post_tl, post_is_deleted, post_draft
                    FROM posts 
                      WHERE post_is_deleted != 1 AND post_tl = 0 AND post_draft != 1";

        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Все пространства для Sitemap
    public static function getTopicsSitemap()
    {
        $sql = "SELECT topic_slug FROM topics";

        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Посты по id пространства для rss
    public static function getPostsFeed($topic_slug)
    {
         $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_translation,
                    post_draft,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_merged_id,
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    user_id, user_login, user_avatar
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(topic_id), 
                                MAX(topic_slug), 
                                MAX(topic_title),
                                MAX(relation_topic_id), 
                                relation_post_id,
                                GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list
                                FROM topics      
                                LEFT JOIN topics_post_relation 
                                    on topic_id = relation_topic_id
                                GROUP BY relation_post_id  
                        ) AS rel
                            ON rel.relation_post_id = post_id 
                            INNER JOIN users ON user_id = post_user_id
                                WHERE topic_list LIKE :qa
                                AND post_is_deleted = 0 AND post_tl = 0 AND post_draft != 1 
                                ORDER BY post_top DESC, post_date DESC LIMIT 1000";

        return DB::run($sql, ['qa' => "%" . $topic_slug . "%"])->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getTopicSlug($topic_slug)
    {
        $sql = "SELECT 
                    topic_id,
                    topic_title,
                    topic_description,
                    topic_slug,
                    topic_img
                        FROM topics WHERE topic_slug = :topic_slug";

        return DB::run($sql, ['topic_slug' => $topic_slug])->fetch(PDO::FETCH_ASSOC);
    }
}
