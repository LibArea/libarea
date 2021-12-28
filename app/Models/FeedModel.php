<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class FeedModel extends MainModel
{
    // Получаем посты по условиям
    public static function feed($page, $limit, $uid, $sheet, $type, $data)
    {
        if ($type == 'topic') {
            $qa         = $data['facet_slug'];
            $string     = "WHERE facet_list LIKE :qa AND post_type != 'page'";
            if ($sheet == 'recommend') {
                $qa     = $data['facet_slug'];
                $string = "WHERE facet_list LIKE :qa AND post_is_recommend = 1 AND post_type != 'page'";
            }
        } elseif ($type == 'admin') {
            $selection  = 0;
            $string     = "WHERE post_user_id != :selection";
            if ($sheet == 'posts.ban') {
                $string = "WHERE post_is_deleted = 1";
            }
        } elseif ($type == 'item') {
            $selection  = $data['item_url_domain'];
            $string     = "WHERE post_url_domain  = :selection AND post_draft = 0";
        } else {
            $selection  = $data['post_user_id'];
            $string     = "WHERE post_user_id  = :selection AND post_draft = 0";
        }

        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL (trust_level)
        $display = '';
        if ($uid['user_trust_level'] != 5) {
            $trust_level = "AND post_tl <= " . $uid['user_trust_level'];
            if ($uid['user_id'] == 0) {
                $trust_level = "AND post_tl = 0";
            }

            $display = "AND post_is_deleted = 0 $trust_level";
        }

        // По времени или по количеству ответов 
        $sort = "ORDER BY post_answers_count DESC";
        if ($sheet == 'feed' || $sheet == 'all') {
            $sort = "ORDER BY post_top DESC, post_date DESC";
        } elseif ($type == 'admin') {
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
                    user_id, user_login, user_avatar, 
                    favorite_tid, favorite_user_id, favorite_type
                    
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                                relation_post_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets      
                                LEFT JOIN facets_posts_relation 
                                    on facet_id = relation_facet_id 
                                GROUP BY relation_post_id  
                        ) AS rel
                            ON rel.relation_post_id = post_id 
                            
                            INNER JOIN users ON user_id = post_user_id
                            LEFT JOIN favorites ON favorite_tid = post_id AND favorite_user_id = " . $uid['user_id'] . " AND favorite_type = 1
                            LEFT JOIN votes_post ON votes_post_item_id = post_id 
                                AND votes_post_user_id = " . $uid['user_id'] . "
                                        
                        $string 
                        $display 
                        $sort LIMIT $start, $limit";

        if ($type == 'topic') {
            $request = ['qa' => "%" . $qa . "@%"];
        } else {
            $request = ['selection' => $selection];
        }

        return DB::run($sql, $request)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество постов
    public static function feedCount($uid, $sheet, $type, $data)
    {
        if ($type == 'topic') {
            $qa         = $data['facet_slug'];
            $string     = "WHERE facet_slug = :qa";
            if ($sheet == 'recommend') {
                $qa     = $data['facet_slug'];
                $string = "WHERE facet_slug = :qa AND post_is_recommend = 1";
            }
        } elseif ($type == 'admin') {
            $selection  = 0;
            $string     = "WHERE post_user_id != :selection";
            if ($sheet == 'posts.ban') {
                $string     = "WHERE post_is_deleted = 1";
            }
        } elseif ($type == 'item') {
            $selection  = $data['item_url_domain'];
            $string     = "WHERE post_url_domain  = :selection";
        } else {
            $selection  = $data['post_user_id'];
            $string     = "WHERE post_user_id  = :selection";
        }

        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL (trust_level)
        $display = '';
        if ($uid['user_trust_level'] != 5) {
            $trust_level = "AND post_tl <= " . $uid['user_trust_level'];
            if ($uid['user_id'] == 0) {
                $trust_level = "AND post_tl = 0";
            }

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

        if ($type == 'topic') {
            $request = ['qa' => $qa];
        } else {
            $request = ['selection' => $selection];
        }

        return DB::run($sql, $request)->rowCount();
    }
}
