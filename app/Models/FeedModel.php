<?php

namespace App\Models;

use DB;

class FeedModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Получаем посты по условиям
    public static function feed($page, $limit, $user, $sheet, $slug)
    {
        switch ($sheet) {
            case 'facet.feed':
                $string     = "WHERE facet_list LIKE :qa AND post_draft = 0 AND post_type = 'post'";
                break;
            case 'questions':
                $string     = "WHERE facet_list LIKE :qa AND post_draft = 0 AND post_feature = 1";
                break;
            case 'posts':
                $string     = "WHERE facet_list LIKE :qa AND post_draft = 0 AND post_feature = 0";
                break;
            case 'recommend':
                $string     = "WHERE facet_list LIKE :qa AND post_is_recommend = 1 AND post_draft = 0 AND post_type = 'post'";
                break;
            case 'web.feed':
                $string     = "WHERE post_url_domain = :qa AND post_draft = 0";
                break;
            case 'admin.posts.user.deleted':
                $string     = "WHERE post_is_deleted = :qa AND post_draft = 0 AND post_type = 'post'";
                break;
            case 'profile.posts':
                $string     = "WHERE post_user_id  = :qa AND post_draft = 0 AND post_type = 'post'";
                break;
        }

        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL (trust_level)
        $display = '';
        if ($user['trust_level'] != 5) {

            $trust_level =  ($user['id'] == 0) ? "AND post_tl = 0" : "AND post_tl <= " . $user['trust_level'];

            $display = "AND post_is_deleted = 0 $trust_level";
        }

        // По времени или по количеству ответов 
        $sort = "ORDER BY post_answers_count DESC";
        if (in_array($sheet, ['facet.feed', 'questions', 'posts'])) {
            $sort = "ORDER BY post_top DESC, post_date DESC";
        } elseif (in_array($sheet, ['admin.posts.all', 'admin.posts.ban', 'profile.posts'])) {
            $sort = "ORDER BY post_date DESC";
        }

        $start  = ($page - 1) * $limit;
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
                    post_hits_count,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_merged_id,
                    post_closed,
                    post_tl,
                    post_ip,
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    votes_post_item_id, votes_post_user_id,
                    u.id, u.login, u.avatar, u.created_at,
                    fav.tid, fav.user_id, fav.action_type
                    
                        FROM posts
                        
                        LEFT JOIN
                        (
                            SELECT 
                                relation_post_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets      
                                LEFT JOIN facets_posts_relation on facet_id = relation_facet_id 
                                    
                                GROUP BY relation_post_id  
                        ) AS rel ON rel.relation_post_id = post_id 
                            
                            INNER JOIN users u ON u.id = post_user_id
                            LEFT JOIN favorites fav ON fav.tid = post_id AND fav.user_id = " . $user['id'] . " AND fav.action_type = 'post'
                            LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = " . $user['id'] . "
                                        
                        $string 
                        $display 
                        $sort LIMIT :start, :limit";


        $request = ['qa' => $slug, 'start' => $start, 'limit' => $limit];
        if (in_array($sheet, ['facet.feed', 'recommend', 'questions', 'posts'])) {
            $request = ['qa' => "%" . $slug . "@%", 'start' => $start, 'limit' => $limit];
        }

        return DB::run($sql, $request)->fetchAll();
    }

    // Количество постов
    public static function feedCount($user, $sheet, $slug)
    {
        switch ($sheet) {
            case 'facet.feed':
                $string     = "WHERE facet_slug = :qa AND post_type != 'page'";
                break;
            case 'questions':
                $string     = "WHERE facet_slug = :qa AND post_type != 'page' AND post_feature = 1";
                break;
            case 'posts':
                $string     = "WHERE facet_slug = :qa AND post_type != 'page' AND post_feature = 0";
                break;
            case 'recommend':
                $string     = "WHERE facet_slug = :qa AND post_is_recommend = 1 AND post_type != 'page'";
                break;
            case 'web.feed':
                $string     = "WHERE post_url_domain = :qa AND post_draft = 0";
                break;
            case 'admin.posts.all':
                $string     = "WHERE post_user_id != :qa";
                break;
            case 'admin.posts.ban':
                $string     = "WHERE post_is_deleted = :qa";
                break;
            case 'profile.posts':
                $string     = "WHERE post_user_id  = :qa AND post_draft = 0";
                break;
        }

        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL (trust_level)
        $display = '';
        if ($user['trust_level'] != 5) {

            $trust_level = ($user['id'] == 0) ? "AND post_tl = 0" : "AND post_tl <= " . $user['trust_level'];

            $display = "AND post_is_deleted = 0 $trust_level";
        }

        $sql = "SELECT 
                    post_id,
                    post_published,
                    post_closed,
                    post_tl,
                    post_url_domain,
                    post_user_id,
                    post_is_deleted,
                    post_is_recommend,
                    facet_id, facet_slug,
                    relation_facet_id, relation_post_id
                        FROM posts
                            LEFT JOIN facets_posts_relation on (post_id = relation_post_id)
                            LEFT JOIN facets on (facet_id = relation_facet_id)
                            $string $display ";

        $request = ['qa' => $slug];

        return DB::run($sql, $request)->rowCount();
    }
}
