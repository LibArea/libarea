<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class RssModel extends Model
{
    /**
     * The last 500 posts
     * Последние 500 постов
     */
    public static function getPosts()
    {
        $sql = "SELECT post_id, post_slug, post_title, post_content, post_date, post_content_img FROM posts 
					WHERE post_is_deleted = 0 AND post_tl = 0 AND post_draft = 0 ORDER BY post_id DESC LIMIT 500";

        return  DB::run($sql)->fetchAll();
    }

    /**
     * All posts for Sitemap
     * Все посты для Sitemap
     */
    public static function getPostsSitemap()
    {
        $sql = "SELECT post_id, post_slug FROM posts WHERE post_is_deleted = 0 AND post_tl = 0 AND post_draft = 0";

        return  DB::run($sql)->fetchAll();
    }

    /**
     * All Topics for Sitemap
     * Все Темы для Sitemap
     */
    public static function getTopicsSitemap()
    {
        $sql = "SELECT facet_slug FROM facets WHERE facet_type = 'topic'";

        return  DB::run($sql)->fetchAll();
    }

    /**
     * Posts by id Topics for rss
     * Посты по id Темы для rss
     *
     * @param [type] $facet_slug
     */
    public static function getPostsFeed($facet_slug)
    {
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_feature,
                    post_translation,
                    post_draft,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
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
                    id, login, avatar
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(facet_id), 
                                MAX(facet_slug), 
                                MAX(facet_title),
                                MAX(relation_facet_id), 
                                relation_post_id,
                                GROUP_CONCAT(facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets      
                                LEFT JOIN facets_posts_relation 
                                    on facet_id = relation_facet_id
                                GROUP BY relation_post_id  
                        ) AS rel
                            ON rel.relation_post_id = post_id 
                            INNER JOIN users ON id = post_user_id
                                WHERE facet_list LIKE :qa
                                    AND post_is_deleted = 0 AND post_tl = 0 AND post_draft = 0 AND post_hidden = 0
                                        ORDER BY post_top DESC, post_date DESC LIMIT 1000";

        return DB::run($sql, ['qa' => "%" . $facet_slug . "%"])->fetchAll();
    }

    public static function getTopicSlug($facet_slug)
    {
        $sql = "SELECT facet_id, facet_title, facet_description, facet_slug FROM facets WHERE facet_slug = :facet_slug";

        return DB::run($sql, ['facet_slug' => $facet_slug])->fetch();
    }
}
