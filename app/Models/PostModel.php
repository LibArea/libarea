<?php

namespace App\Models;

use UserData;
use DB;

class PostModel extends \Hleb\Scheme\App\Models\MainModel
{   
    public static function create($params)
    {
        $sql = "INSERT INTO posts(post_title, 
                                    post_content, 
                                    post_content_img,
                                    post_thumb_img,
                                    post_related,
                                    post_tl,
                                    post_slug,
                                    post_feature,
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
                                    :post_tl,
                                    :post_slug,
                                    :post_feature,
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

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch();

        return $sql_last_id['last_id'];
    }

    public static function getSlug($slug)
    {
        $sql = "SELECT post_slug FROM posts WHERE post_slug = :slug";

        return DB::run($sql, ['slug' => $slug])->fetch();
    }

    public static function getPostId($post_id)
    {
        $sql = "SELECT post_id, post_title, post_slug FROM posts WHERE post_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id])->fetchAll();
    }

    // Full post 
    // Полная версия поста  
    public static function getPost($params, $name, $user)
    {
        $user_id = $user['id'];
        $sort = $name == 'slug' ?  "post_slug = :params" : "post_id = :params";

        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_feature,
                    post_translation,
                    post_type,
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
                    u.id,
                    u.login,
                    u.avatar,
                    up_count,
                    my_post,
                    votes_post_item_id,
                    votes_post_user_id,
                    fav.tid, 
                    fav.user_id, 
                    fav.action_type
                        FROM posts
                        LEFT JOIN users u ON u.id = post_user_id
                        LEFT JOIN favorites fav ON fav.tid = post_id AND fav.user_id = $user_id AND fav.action_type = 'post' 
                        LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = $user_id
                            WHERE $sort AND post_tl <= :trust_level";

        $data = ['params' => $params, 'trust_level' => $user['trust_level']];

        return DB::run($sql, $data)->fetch();
    }

    // Recommended posts
    // This should not be your own post, whose TL is not higher than the participant, the post is not a draft, not deleted, etc.
    // Рекомендованные посты
    // Это должен быть не свой пост, у которого TL не выше участника, пост не черновик, не удален и т.д.
    public static function postSimilars($post_id, $facet_id, $limit = 5)
    {
        $tl = UserData::getUserTl() == null ? 0 : UserData::getUserTl();
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_feature,
                    post_answers_count,
                    post_type
                        FROM posts
                            LEFT JOIN facets_posts_relation on post_id = relation_post_id
                                WHERE post_id != :post_id 
                                    AND post_is_deleted = 0
                                    AND post_draft = 0
                                    AND post_tl <= :tl 
                                    AND post_user_id != :user_id
                                    AND post_type = 'post'
                                    AND relation_facet_id = :facet_id
                                        ORDER BY post_id DESC LIMIT :limit";

        return DB::run($sql, ['post_id' => $post_id, 'user_id' => UserData::getUserId(), 'tl' => $tl, 'limit' => $limit, 'facet_id' => $facet_id])->fetchAll();
    }

    // $type (comments / answers / hits)
    public static function updateCount($post_id, $type)
    {
        $sql = "UPDATE posts SET post_" . $type . "_count = (post_" . $type . "_count + 1) WHERE post_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id]);
    }


    public static function updateViews($post_id, $user_id)
    {
        if (!self::checkViews($post_id, $user_id)) {
            $sql = "INSERT INTO posts_view(view_post_id, view_user_id) VALUES(:post_id, :user_id)";
            DB::run($sql, ['post_id' => $post_id, 'user_id' => $user_id]);
        }

        return true;
    }

    public static function checkViews($post_id, $user_id)
    {
        $sql = "SELECT view_post_id FROM posts_view WHERE view_post_id = :post_id AND view_user_id = :user_id";

        return DB::run($sql, ['post_id' => $post_id, 'user_id' => $user_id])->fetch();
    }

    public static function editPost($data)
    {
        $params = [
            'post_title'            => $data['post_title'],
            'post_slug'             => $data['post_slug'],
            'post_feature'          => $data['post_feature'],
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
                    post_slug             = :post_slug,
                    post_feature          = :post_feature,
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

    // Related posts
    // Связанные посты
    public static function postRelated($post_related)
    {
        if (empty($post_related)) return false;

        $sql = "SELECT 
                    post_id as id, 
                    post_title as value,
                    post_slug,
                    post_content_img
                        FROM posts 
                           WHERE post_id IN(0, " . $post_related . ") AND post_is_deleted = 0 AND post_tl = 0";

        return DB::run($sql)->fetchAll();
    }

    public static function postRelatedAll()
    {
        $sql = "SELECT post_id, post_title, post_slug as slug FROM posts WHERE post_is_deleted = 0 AND post_tl = 0";

        return DB::run($sql)->fetchAll();
    }

    // Removing the cover
    // Удаление обложки
    public static function setPostImgRemove($post_id)
    {
        $sql_two = "UPDATE posts SET post_content_img = '' WHERE post_id = :post_id";

        return DB::run($sql_two, ['post_id' => $post_id]);
    }

    // Add post to profile
    // Добавить пост в профиль
    public static function setPostProfile($post_id, $user_id)
    {
        $result = self::getPostProfile($post_id, $user_id);

        if (is_array($result)) {

            $sql = "UPDATE users SET my_post = :my_post_id WHERE id = :id AND my_post = :post_id";

            DB::run($sql, ['post_id' => $post_id, 'id' => $user_id, 'my_post_id' => 0]);

            return 'del';
        }

        $sql = "UPDATE users SET my_post = :post_id WHERE id = :id";

        DB::run($sql, ['post_id' => $post_id, 'id' => $user_id]);

        return 'add';
    }

    public static function getPostProfile($post_id, $user_id)
    {
        $sql = "SELECT my_post FROM users WHERE my_post = :post_id AND id = :id";

        return  DB::run($sql, ['post_id' => $post_id, 'id' => $user_id])->fetch();
    }

    public static function getPostMerged($post_id)
    {
        $sql = "SELECT post_id, post_title, post_slug FROM posts WHERE post_merged_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id])->fetchAll();
    }

    public static function getPostTopic($post_id, $type)
    {
        $condition = $type == 'blog' ? 'blog' : 'topic';

        $sql = "SELECT
                    facet_id,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_type,
                    facet_user_id,
                    facet_short_description,
                    relation_facet_id,
                    relation_post_id,
                    signed_facet_id, 
                    signed_user_id
                        FROM facets  
                        INNER JOIN facets_posts_relation ON relation_facet_id = facet_id
                        LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = :user_id
                            WHERE relation_post_id  = :post_id AND facet_type = '$condition'";

        return DB::run($sql, ['post_id' => $post_id, 'user_id' => UserData::getUserId()])->fetchAll();
    }

    public static function getPostFacet($post_id, $type)
    {
        $sql = "SELECT
                    facet_id id,
                    facet_title value
                        FROM facets  
                        INNER JOIN facets_posts_relation ON relation_facet_id = facet_id
                            WHERE relation_post_id  = :post_id AND facet_type = :type";

        return DB::run($sql, ['post_id' => $post_id, 'type' => $type])->fetchAll();
    }

    // Check if the domain exists 
    // Проверим наличие домена
    public static function availabilityDomain($domain)
    {
        $sql = "SELECT post_url_domain FROM posts WHERE post_url_domain = :domain AND post_is_deleted = 0";

        return DB::run($sql, ['domain' => $domain])->fetch();
    }

    // 10 domains
    // 10 доменов
    public static function listDomain($domain)
    {
        $sql = "SELECT DISTINCT post_id, post_url_domain FROM posts
                    WHERE post_url_domain != :domain AND post_url_domain != '' AND post_is_deleted = 0 ORDER BY post_id DESC LIMIT 10";

        return DB::run($sql, ['domain' => $domain])->fetchAll();
    }

    // Who is following this question/post
    // Кто подписан на данный вопрос / пост
    public static function getFocusUsersPost($post_id)
    {
        $sql = "SELECT signed_post_id, signed_user_id FROM posts_signed WHERE signed_post_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id])->fetchAll();
    }

    // List of viewed posts
    // Список просмотренных постов
    public static function getViewPostUser()
    {
        $sql = "SELECT view_post_id FROM posts_view WHERE view_user_id = :user_id ORDER BY view_date DESC LIMIT 25";

        return DB::run($sql, ['user_id' => UserData::getUserId()])->fetchAll();
    }

    // List of posts to which the participant has subscribed
    // Список постов на которые подписался участник
    public static function getFocusPostUser()
    {
        $sql = "SELECT signed_post_id FROM posts_signed WHERE signed_user_id = :user_id";

        return DB::run($sql, ['user_id' => UserData::getUserId()])->fetchAll();
    }

    public static function getPostsListUser($type)
    {
        $user_id = UserData::getUserId();
        
        $result = [];
        if ($type == 'subscribed') {
            foreach (self::getFocusPostUser() as $ind => $row) {
                $result[$ind] = $row['signed_post_id'];
            }
        } else {
            foreach (self::getViewPostUser() as $ind => $row) {
                $result[$ind] = $row['view_post_id'];
            }
        }

        if (empty($result)) return false;

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
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    votes_post_item_id, votes_post_user_id,
                    u.id, u.login, u.avatar, 
                    fav.tid, fav.user_id, fav.action_type
                    
                        FROM posts
                        LEFT JOIN
                        ( SELECT 
                                relation_post_id,
                                GROUP_CONCAT(facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets  
                                    LEFT JOIN facets_posts_relation on facet_id = relation_facet_id
                                        GROUP BY relation_post_id
                        ) AS rel
                            ON rel.relation_post_id = post_id 

            INNER JOIN users u ON u.id = post_user_id
            LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = $user_id
            LEFT JOIN favorites fav ON fav.tid = post_id AND fav.user_id = $user_id AND fav.action_type = 'post'
                WHERE post_id IN(" . implode(',', $result) . ") AND post_draft = 0 AND post_is_deleted = 0 ORDER BY post_id DESC LIMIT 50";

        return DB::run($sql)->fetchAll();
    }

    // Last 5 pages by facet 
    // Последние 5 страниц по фасету
    public static function recent($facet_id, $post_id)
    {
        $sort = ($post_id > 0) ? 'AND post_id != ' . $post_id : '';
        $sql = "SELECT 
                    post_id,
                    post_slug,
                    post_title,
                    post_type
                        FROM facets_posts_relation 
                            LEFT JOIN posts on post_id = relation_post_id
                                WHERE relation_facet_id = :facet_id AND post_type = 'page'
                                    $sort
                                        ORDER BY post_id DESC LIMIT 5";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }
}
