<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class PostModel extends MainModel
{
    // Добавляем пост
    public static function addPost($data)
    {
        $result = self::getSlug($data['post_slug']);
        if ($result) {
            $data['post_slug'] =  $data['post_slug'] . "-";
        }

        $params = [
            'post_title'        =>  $data['post_title'],
            'post_content'      =>  $data['post_content'],
            'post_content_img'  =>  $data['post_content_img'],
            'post_thumb_img'    =>  $data['post_thumb_img'],
            'post_related'      =>  $data['post_related'],
            'post_merged_id'    =>  $data['post_merged_id'],
            'post_tl'           =>  $data['post_tl'],
            'post_slug'         =>  $data['post_slug'],
            'post_type'         =>  $data['post_type'],
            'post_translation'  =>  $data['post_translation'],
            'post_draft'        =>  $data['post_draft'],
            'post_ip'           =>  $data['post_ip'],
            'post_published'    =>  $data['post_published'],
            'post_user_id'      =>  $data['post_user_id'],
            'post_closed'       =>  $data['post_closed'],
            'post_top'          =>  $data['post_top'],
            'post_url'          =>  $data['post_url'],
            'post_url_domain'   =>  $data['post_url_domain'],
        ];

        $sql = "INSERT INTO posts(post_title, 
                                    post_content, 
                                    post_content_img,
                                    post_thumb_img,
                                    post_related,
                                    post_merged_id,
                                    post_tl,
                                    post_slug,
                                    post_type,
                                    post_translation,
                                    post_draft,
                                    post_ip,
                                    post_published,
                                    post_user_id,
                                    post_closed,
                                    post_top,
                                    post_url,
                                    post_url_domain)
                                    
                                VALUES(:post_title, 
                                    :post_content, 
                                    :post_content_img,
                                    :post_thumb_img,
                                    :post_related,
                                    :post_merged_id,
                                    :post_tl,
                                    :post_slug,
                                    :post_type,
                                    :post_translation,
                                    :post_draft,
                                    :post_ip,
                                    :post_published,
                                    :post_user_id,
                                    :post_closed,
                                    :post_top,
                                    :post_url,
                                    :post_url_domain)";

        DB::run($sql, $params);

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);

        return $sql_last_id['last_id'];
    }

    public static function getSlug($slug)
    {
        $sql = "SELECT 
                    post_slug 
                        FROM posts WHERE post_slug = :slug";

        return DB::run($sql, ['slug' => $slug])->fetch(PDO::FETCH_ASSOC);
    }

    // Полная версия поста  
    public static function getPostSlug($slug, $user_id, $trust_level)
    {
        // Ограничение по TL
        if ($user_id == 0) {
            $trust_level = 0;
        }

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
                    post_ip,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_closed, 
                    post_tl,
                    post_lo,
                    post_top,
                    post_url,
                    post_modified,
                    post_related,
                    post_merged_id,
                    post_is_recommend,
                    post_url_domain,
                    post_hits_count,
                    post_is_deleted,
                    user_id,
                    user_login,
                    user_avatar,
                    user_my_post,
                    votes_post_item_id,
                    votes_post_user_id,
                    favorite_tid, 
                    favorite_user_id, 
                    favorite_type
                        FROM posts
                        LEFT JOIN users ON user_id = post_user_id
                        LEFT JOIN favorites ON favorite_tid = post_id AND favorite_user_id = :user_id AND favorite_type = 1 
                        LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = :user_id
                            WHERE post_slug = :slug AND post_tl <= :trust_level";

        return DB::run($sql, ['slug' => $slug, 'user_id' => $user_id, 'trust_level' => $trust_level])->fetch(PDO::FETCH_ASSOC);
    }

    // Получаем пост по id
    public static function getPostId($post_id)
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
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url,
                    post_related,
                    post_merged_id,
                    post_is_recommend,
                    post_url_domain,
                    post_is_deleted,
                    user_id,
                    user_login,
                    user_avatar
                        FROM posts
                        LEFT JOIN users ON user_id = post_user_id
                            WHERE post_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Рекомендованные посты
    public static function postsSimilar($post_id, $uid, $limit)
    {

        $tl = $uid['user_trust_level'];
        if ($uid['user_trust_level'] == null) {
            $tl = 0;
        }

        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_tl,
                    post_answers_count,
                    post_draft,
                    post_user_id,
                    post_is_deleted
                        FROM posts
                            WHERE post_id < :post_id 
                                AND post_is_deleted = 0
                                AND post_draft = 0
                                AND post_tl <= :tl 
                                AND post_user_id != :user_id
                                ORDER BY post_id DESC LIMIT $limit";

        return DB::run($sql, ['post_id' => $post_id, 'user_id' => $uid['user_id'], 'tl' => $tl])->fetchall(PDO::FETCH_ASSOC);
    }

    // Пересчитываем количество
    // $type (comments / answers / hits)
    public static function updateCount($post_id, $type)
    {
        $sql = "UPDATE posts SET post_" . $type . "_count = (post_" . $type . "_count + 1) WHERE post_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id]);
    }

    // Редактирование поста
    public static function editPost($data)
    {
        $params = [
            'post_title'            => $data['post_title'],
            'post_type'             => $data['post_type'],
            'post_translation'      => $data['post_translation'],
            'post_date'             => $data['post_date'],
            'post_user_id'          => $data['post_user_id'],
            'post_draft'            => $data['post_draft'],
            'post_modified'         => date("Y-m-d H:i:s"),
            'post_content'          => $data['post_content'],
            'post_content_img'      => $data['post_content_img'],
            'post_related'          => $data['post_related'],
            'post_merged_id'        => $data['post_merged_id'],
            'post_tl'               => $data['post_tl'],
            'post_closed'           => $data['post_closed'],
            'post_top'              => $data['post_top'],
            'post_id'               => $data['post_id'],
        ];

        $sql = "UPDATE posts SET 
                    post_title            = :post_title,
                    post_type             = :post_type,
                    post_translation      = :post_translation,
                    post_date             = :post_date,
                    post_user_id          = :post_user_id,
                    post_draft            = :post_draft,
                    post_modified         = :post_modified,
                    post_content          = :post_content,
                    post_content_img      = :post_content_img,
                    post_related          = :post_related,
                    post_merged_id        = :post_merged_id,
                    post_tl               = :post_tl,
                    post_closed           = :post_closed,
                    post_top              = :post_top
                         WHERE post_id = :post_id";

        return DB::run($sql, $params);
    }

    // Связанные посты
    public static function postRelated($post_related)
    {
        $string = "post_id IN(0) AND";
        if ($post_related) {
            $string = "post_id IN(0, " . $post_related . ") AND";
        }
        
        $sql = "SELECT 
                    post_id, 
                    post_title, 
                    post_slug, 
                    post_type, 
                    post_draft, 
                    post_related, 
                    post_is_deleted
                        FROM posts 
                            WHERE $string post_is_deleted = 0 AND post_tl = 0";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Удаление обложки
    public static function setPostImgRemove($post_id)
    {
        $sql_two = "UPDATE posts SET post_content_img = '' WHERE post_id = :post_id";

        return DB::run($sql_two, ['post_id' => $post_id]);
    }

    // Добавить пост в профиль
    public static function addPostProfile($post_id, $user_id)
    {
        $sql = "UPDATE users SET user_my_post = :post_id WHERE user_id = :user_id";

        return DB::run($sql, ['post_id' => $post_id, 'user_id' => $user_id]);
    }

    // Удаление поста в профиле
    public static function deletePostProfile($post_id, $user_id)
    {
        $sql = "UPDATE users SET user_my_post = :my_post_id WHERE user_id = :user_id AND user_my_post = :post_id";

        return DB::run($sql, ['post_id' => $post_id, 'user_id' => $user_id, 'my_post_id' => 0]);
    }

    // Удален пост или нет
    public static function isThePostDeleted($post_id)
    {
        $sql = "SELECT post_id, post_is_deleted
                    FROM posts
                        WHERE post_id = :post_id";

        $result = DB::run($sql, ['post_id' => $post_id])->fetch(PDO::FETCH_ASSOC);

        return $result['post_is_deleted'];
    }

    public static function getPostTopic($post_id)
    {
        $sql = "SELECT
                    topic_id,
                    topic_title,
                    topic_slug,
                    relation_topic_id,
                    relation_post_id
                        FROM topics  
                        INNER JOIN topics_post_relation ON relation_topic_id = topic_id
                            WHERE relation_post_id  = :post_id";

        return DB::run($sql, ['post_id' => $post_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Кто ответил в посте
    // TODO: эксперемент
    public static function getReplyUserPost($post_id)
    {
        // TODO: временно проверим группировку
        // user_id, 
        // user_login,
        // user_avatar,
        // LEFT JOIN users ON user_id = answer_user_id OR user_id = comment_user_id
        $sql = "SELECT 
                  answer_id,
                  answer_user_id,
                  answer_post_id,
                  answer_is_deleted, 
                  rel.* 
                      FROM answers
                      LEFT JOIN
                        ( SELECT 
                              MAX(comment_id),
                              MAX(comment_user_id),
                              MAX(comment_post_id) as comm_post_id,
                              MAX(comment_is_deleted),
                              comment_answer_id 
                                FROM comments  
                                WHERE comment_is_deleted = 0  
                                  GROUP BY comment_answer_id
                        ) AS rel
                            ON rel.comment_answer_id = answer_id
 
                  WHERE answer_post_id = 312";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
