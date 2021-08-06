<?php

namespace App\Models;

use DB;
use PDO;

class HomeModel extends \MainModel
{
    // Посты на центральной странице
    public static function feed($page, $limit, $space_user, $uid, $type)
    {
        $result = array();
        foreach ($space_user as $ind => $row) {
            $result[$ind] = $row['signed_space_id'];
        }

        // Мы должны сформировать список пространств по умолчанию (в config)
        // и добавить условие показа постов, рейтинг которых достигает > N+ значения
        // в первый час размещения, но не вошедшие в пространства по умолчанию к показу
        if ($uid['id'] == 0) {
            $string = "WHERE post_draft = 0";
        } else {
            if ($type == 'all') {
                $string = "WHERE post_draft = 0";
            } else {
                if ($result) {
                    $string = "WHERE post_space_id IN(1, " . implode(',', $result) . ") AND post_draft = 0";
                } else {
                    $string = "WHERE post_space_id IN(1) AND post_draft = 0";
                }
            }
        }

        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL
        $display = '';
        if ($uid['trust_level'] != 5) {
            $tl = "AND post_tl <= " . $uid['trust_level'];
            if ($uid['id'] == 0) {
                $tl = "AND post_tl = 0";
            }

            $display = "AND post_is_deleted = 0 AND space_feed = 0 $tl";
            if ($type == 'all') {
                $display = "AND post_is_deleted = 0 $tl";
            }
        }

        $sort = "ORDER BY post_votes DESC";
        if ($type == 'feed' || $type == 'all') {
            $sort = "ORDER BY post_top DESC, post_date DESC";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_translation,
                    post_draft,
                    post_space_id,
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
                    votes_post_item_id, votes_post_user_id,
                    id, login, avatar, 
                    space_id, space_slug, space_name, space_color
                    
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

            INNER JOIN users ON id = post_user_id
            INNER JOIN spaces ON space_id = post_space_id
            LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = " . $uid['id'] . "
            
            $string $display $sort LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество постов
    public static function feedCount($space_user, $uid)
    {
        $result = array();
        foreach ($space_user as $ind => $row) {
            $result[$ind] = $row['signed_space_id'];
        }

        $string = "WHERE post_space_id IN(1)";
        if ($result) {
            $string = "WHERE post_space_id IN(1, " . implode(',', $result) . ")";
        }

        // Учитываем подписку на пространства
        if ($uid['id'] == 0) {
            $string = '';
        }

        // Учитываем TL
        $display = '';
        if ($uid['trust_level'] != 5) {
            $tl = "AND post_tl <= " . $uid['trust_level'];
            if ($uid['id'] == 0) {
                $tl = "AND post_tl = 0";
            }
            $display = "AND post_is_deleted = 0 AND space_feed = 0 $tl";
        }

        $sql = "SELECT post_id, post_space_id, space_id
                FROM posts
                INNER JOIN spaces ON space_id = post_space_id
                $string $display";

        return DB::run($sql)->rowCount();
    }

    // Последние 5 ответа на главной
    public static function latestAnswers($uid)
    {
        $user_answer = "AND space_feed = 0 AND post_tl = 0";
        if ($uid['id']) {
            $user_answer = "AND space_feed = 0 AND answer_user_id != " . $uid['id'] . " AND post_tl <= " . $uid['trust_level'];

            if ($uid['trust_level'] != 5) {
                $user_answer = "AND answer_user_id != " . $uid['id'];
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
                    post_space_id,
                    id,
                    login,
                    avatar,
                    space_id,
                    space_color,
                    space_feed
                        FROM answers 
                        LEFT JOIN posts ON post_id = answer_post_id
                        LEFT JOIN users ON id = answer_user_id
                        LEFT JOIN spaces ON post_space_id = space_id 
                        WHERE answer_is_deleted = 0 
                        $user_answer 
                        ORDER BY answer_id DESC LIMIT 5";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Пространства все / подписан
    public static function getSubscriptionSpaces($user_id)
    {
        $sql = "SELECT 
                    space_id, 
                    space_slug, 
                    space_name,
                    space_img,
                    space_user_id,
                    space_is_delete,
                    signed_space_id, 
                    signed_user_id
 
                        FROM spaces 
                        LEFT JOIN spaces_signed ON signed_space_id = space_id AND signed_user_id = :user_id 
                        WHERE space_is_delete != 1 AND signed_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
