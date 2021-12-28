<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;
use Base;

class HomeModel extends MainModel
{
    // Posts on the central page
    // Посты на центральной странице
    public static function feed($page, $limit, $topics_user, $uid, $type)
    {

        $result = [];
        foreach ($topics_user as $ind => $row) {
            $result[$ind] = $row['signed_facet_id'];
        }

        $string = "";
        if ($type != 'main.all' && $type != 'main.top') {
            if ($uid['user_id'] == 0) {
                $string = "";
            } else {
                $string = "AND relation_facet_id IN(0)";
                if ($result) $string = "AND relation_facet_id IN(" . implode(',', $result ?? []) . ")";
            }
        }

        $display = "AND post_is_deleted = 0 AND post_tl <= " . $uid['user_trust_level'];
        if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) $display = "";

        $sort = "ORDER BY post_votes and post_date > CURDATE()-INTERVAL 3 WEEK DESC";
        if ($type == 'main.feed' || $type == 'main.all') $sort = "ORDER BY post_top DESC, post_date DESC";

        $start  = ($page - 1) * $limit;

        $sql = "SELECT DISTINCT
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
                post_lo,
                post_top,
                post_url_domain,
                post_is_deleted,
                rel.*,
                votes_post_item_id, votes_post_user_id,
                user_id, user_login, user_avatar, 
                favorite_tid, favorite_user_id, favorite_type 
  
            FROM facets_posts_relation 
            LEFT JOIN posts ON relation_post_id = post_id
            
            LEFT JOIN (
                SELECT 
                    relation_post_id,
                    GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                    FROM facets
                    LEFT JOIN facets_posts_relation 
                        on facet_id = relation_facet_id
                        GROUP BY relation_post_id
            ) AS rel
                 ON rel.relation_post_id= post_id
                LEFT JOIN users ON user_id = post_user_id
                LEFT JOIN favorites ON favorite_tid = post_id 
                    AND favorite_user_id = :user_id AND favorite_type = 1  
                LEFT JOIN votes_post 
                    ON votes_post_item_id = post_id AND votes_post_user_id = :user_id

                WHERE post_type != 'page' AND post_draft = 0 $string $display $sort LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $uid['user_id']])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function feedCount($topics_user, $uid, $type)
    {
        $result = [];
        foreach ($topics_user as $ind => $row) {
            $result[$ind] = $row['signed_facet_id'];
        }

        $string = "";
        if ($type != 'all' && $type != 'top') {
            if ($uid['user_id'] == 0) {
                $string = "";
            } else {
                $string = "AND f_id IN(0)";
                if ($result) $string = "AND f_id IN(" . implode(',', $result ?? []) . ")";
            }
        }

        $display = "AND post_is_deleted = 0 AND post_tl <= " . $uid['user_trust_level'];
        if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) $display = "";

        $sql = "SELECT 
                    post_id,
                    post_draft,
                    post_published,
                    post_user_id,
                    post_tl,
                    post_is_deleted,
                    rel.*,
                    user_id
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                            MAX(facet_id) as f_id,
                                relation_post_id
                                FROM facets  
                                LEFT JOIN facets_posts_relation 
                                    on facet_id = relation_facet_id
                                    GROUP BY relation_post_id
                        ) AS rel
                            ON rel.relation_post_id = post_id 

            INNER JOIN users ON user_id = post_user_id
            WHERE post_draft = 0       
            $string $display";

        return DB::run($sql)->rowCount();
    }

    // The last 5 responses on the main page
    // Последние 5 ответа на главной
    public static function latestAnswers($uid)
    {
        $tl = $uid['user_trust_level'];
        $user_answer = "AND post_tl = :zero";
        if ($uid['user_id']) {
            $user_answer = "AND answer_user_id != :id AND post_tl <= $tl";
            if ($uid['user_trust_level'] != 5) {
                $user_answer = "AND answer_user_id != :id AND post_tl <= $tl";
            }
        }

        $sql = "SELECT 
                    answer_id,
                    answer_post_id,
                    answer_user_id,
                    answer_is_deleted,
                    answer_content,
                    answer_date,
                    post_id,
                    post_tl,
                    post_slug,
                    post_is_deleted,
                    user_id,
                    user_login,
                    user_avatar
                        FROM answers 
                        LEFT JOIN posts ON post_id = answer_post_id
                        LEFT JOIN users ON user_id = answer_user_id
                        WHERE answer_is_deleted = :zero AND post_is_deleted = :zero
                        $user_answer 
                        ORDER BY answer_id DESC LIMIT 5";

        return DB::run($sql, ['id' => $uid['user_id'], 'zero' => 0])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Facets (themes, blogs) all / subscribed
    // Фасеты (темы, блоги) все / подписан
    public static function subscription($user_id)
    {
        $sql = "SELECT 
                    facet_id, 
                    facet_slug, 
                    facet_title,
                    facet_user_id,
                    facet_img,
                    facet_type,
                    signed_facet_id, 
                    signed_user_id                    
                        FROM facets 
                        JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = :user_id  
                            AND facet_type != 'section' 
                                ORDER BY facet_id DESC";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
