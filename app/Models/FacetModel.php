<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class FacetModel extends MainModel
{
    // Все фасеты
    // Все фасеты
    public static function getFacetsAll($page, $limit, $user_id, $sort)
    {
        switch ($sort) {
            case 'topics.my':
                $signet = "WHERE facet_type = 'topic' AND signed_user_id = :user_id ORDER BY facet_count DESC";
                break;
            case 'topics.new':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_id DESC";
                break;
            case 'blogs.all':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_count DESC";
                break;
            case 'blogs.new':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_id DESC";
                break;
            case 'blogs.my':
                $signet = "WHERE facet_type = 'blog' AND signed_user_id = :user_id ORDER BY facet_count DESC";
                break;
            case 'admin.blogs.all':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_id DESC";
                break;
            case 'admin.blogs.ban':
                $signet = "WHERE facet_type = 'blog' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            case 'admin.topics.all':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_id DESC";
                break;
            case 'admin.topics.ban':
                $signet = "WHERE facet_type = 'topic' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            default:
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_count DESC";
                break;
        }

        $start  = ($page - 1) * $limit;
        $sql    = "SELECT 
                    facet_id,
                    facet_title,
                    facet_description,
                    facet_short_description,
                    facet_slug,
                    facet_img,
                    facet_user_id,
                    facet_top_level,
                    facet_focus_count,
                    facet_count,
                    signed_facet_id, 
                    signed_user_id,
                    facet_type,
                    facet_is_deleted
                        FROM facets 
                        LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = :user_id
                        $signet
                        LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFacetsAllCount($user_id, $sort)
    {
        switch ($sort) {
            case 'topics.my':
                $signet = "WHERE facet_type = 'topic' AND signed_user_id = :user_id ORDER BY facet_count DESC";
                break;
            case 'topics.new':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_id DESC";
                break;
            case 'blogs.all':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_count DESC";
                break;
            case 'blogs.new':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_id DESC";
                break;
            case 'blogs.my':
                $signet = "WHERE facet_type = 'blog' AND signed_user_id = :user_id ORDER BY facet_count DESC";
                break;
            case 'admin.blogs.all':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_id DESC";
                break;
            case 'admin.blogs.ban':
                $signet = "WHERE facet_type = 'blog' AND facet_is_deleted = 21 ORDER BY facet_id DESC";
                break;
            case 'admin.topics.all':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_id DESC";
                break;
            case 'admin.topics.ban':
                $signet = "WHERE facet_type = 'topic' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            default:
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_count DESC";
                break;
        }

        $sql    = "SELECT 
                    facet_id,
                    facet_type,
                    signed_facet_id, 
                    signed_user_id,
                    facet_is_deleted
                        FROM facets 
                        LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = :user_id
                        $signet";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    // Информация по фасету (id, slug)
    // Cell information (id, slug) 
    public static function getFacet($params, $name)
    {
        $sort = "facet_id = :params";
        if ($name == 'slug') {
            $sort = "facet_slug = :params";
        }

        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_description,
                    facet_short_description,
                    facet_info,
                    facet_slug,
                    facet_img,
                    facet_cover_art,
                    facet_add_date,
                    facet_seo_title,
                    facet_merged_id,
                    facet_top_level,
                    facet_user_id,
                    facet_tl,
                    facet_post_related,
                    facet_focus_count,
                    facet_count,
                    facet_type,
                    facet_is_web,
                    facet_is_soft,
                    facet_is_deleted
                        FROM facets WHERE $sort";

        return DB::run($sql, ['params' => $params])->fetch(PDO::FETCH_ASSOC);
    }

    public static function addPostFacets($rows, $post_id)
    {
        self::deleteRelation($post_id, 'post');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            $sql        = "INSERT INTO facets_posts_relation (relation_facet_id, relation_post_id) 
                                VALUES ($facet_id, $post_id)";

            DB::run($sql);
        }

        return true;
    }

    public static function addItemFacets($rows, $item_id)
    {
        self::deleteRelation($item_id, 'item');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            $sql = "INSERT INTO facets_items_relation (relation_facet_id, relation_item_id) 
                        VALUES ($facet_id, $item_id)";

            DB::run($sql);
        }

        return true;
    }

    // Основные деревья
    // Main trees
    public static function addLowFacetRelation($rows, $topic_id)
    {
        self::deleteRelation($topic_id, 'topic');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            $sql = "INSERT INTO facets_relation (facet_parent_id, facet_chaid_id) 
                        VALUES ($topic_id, $facet_id)";

            DB::run($sql);
        }

        return true;
    }

    // Перекрестные связи
    public static function addLowFacetMatching($rows, $topic_id)
    {
        self::deleteRelation($topic_id, 'matching');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            $sql = "INSERT INTO facets_matching (matching_parent_id, matching_chaid_id) 
                        VALUES ($topic_id, $facet_id)";

            DB::run($sql);
        }

        return true;
    }

    public static function deleteRelation($id, $type)
    {
        $sql = "DELETE FROM facets_items_relation WHERE relation_item_id = $id";
        if ($type == 'post') {
            $sql = "DELETE FROM facets_posts_relation WHERE relation_post_id = $id";
        } elseif ($type == 'topic') {
            $sql = "DELETE FROM facets_relation WHERE facet_parent_id = $id";
        } elseif ($type == 'matching') {
            $sql = "DELETE FROM facets_matching WHERE matching_parent_id = $id";
        }

        return DB::run($sql);
    }

    // Изменение img
    // Changing img
    public static function setImg($facet_id, $img)
    {
        $sql = "UPDATE facets SET facet_img = :img WHERE facet_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id, 'img' => $img]);
    }

    // TOP авторов фасета. Limit 10
    // TOP of facet authors. Limit 10 
    public static function getWriters($facet_id)
    {
        $sql = "SELECT MAX(relation_facet_id), 
                    MAX(relation_post_id), 
                    MAX(post_id), 
                    SUM(post_hits_count) as hits_count, 
                    post_user_id,
                    rel.*
                        FROM facets_posts_relation 
                        LEFT JOIN posts ON relation_post_id = post_id 
                        LEFT JOIN
                        (
                            SELECT 
                                user_id,
                                user_login,                                 
                                user_avatar 
                                FROM users 
                        ) AS rel
                            ON rel.user_id = post_user_id
                 WHERE relation_facet_id = :facet_id  GROUP BY post_user_id 
                 ORDER BY hits_count DESC LIMIT 5";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Вклад участника
    // Participant contribution
    public static function participation($user_id)
    {
        $sql = " SELECT 
                    MAX(post_id), 
                    MAX(post_user_id),
                    MAX(post_is_deleted),
                    SUM(relation_facet_id) as count,
                    rel.*
                    FROM posts
                    LEFT JOIN facets_posts_relation ON relation_post_id = post_id
                    LEFT JOIN
                        ( SELECT 
                            facet_id,
                            facet_slug,
                            facet_title
                            FROM facets
                        ) AS rel
                            ON rel.facet_id = relation_facet_id
                               WHERE post_user_id = :user_id
                               GROUP BY relation_facet_id 
                               ORDER BY count DESC LIMIT 5";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function userReads($user_id)
    {
        $sql = "SELECT 
                    signed_facet_id                  
                        FROM facets_signed 
                            WHERE signed_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function advice($user_id)
    {
        if ($user_id == 0) return true;

        $userReads = self::userReads($user_id);
        $result = [];
        foreach ($userReads as $ind => $row) {
            $result[$ind] = $row['signed_facet_id'];
        }

        $result = $result ? $result : ['1' => 1];
        $sql = "SELECT 
                facet_id,
                facet_title,
                facet_slug,
                facet_img,
                facet_description,
                facet_count
                    FROM facets
                        WHERE facet_id NOT IN(" . implode(',', $result ?? []) . ")
                        ORDER BY facet_count DESC LIMIT 6";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add($data)
    {
        $params = [
            'facet_title'               => $data['facet_title'],
            'facet_description'         => $data['facet_description'],
            'facet_short_description'   => $data['facet_short_description'],
            'facet_slug'                => $data['facet_slug'],
            'facet_img'                 => $data['facet_img'],
            'facet_add_date'            => $data['facet_add_date'],
            'facet_seo_title'           => $data['facet_seo_title'],
            'facet_user_id'             => $data['facet_user_id'],
            'facet_type'                => $data['facet_type'],
        ];

        $sql = "INSERT INTO facets(facet_title, 
                        facet_description, 
                        facet_short_description,
                        facet_slug, 
                        facet_img,
                        facet_add_date,
                        facet_seo_title,
                        facet_user_id,
                        facet_type) 
                            VALUES(:facet_title, 
                                :facet_description, 
                                :facet_short_description, 
                                :facet_slug, 
                                :facet_img, 
                                :facet_add_date,
                                :facet_seo_title,
                                :facet_user_id,
                                :facet_type)";

        DB::run($sql, $params);

        return  DB::run("SELECT LAST_INSERT_ID() as facet_id")->fetch(PDO::FETCH_ASSOC);
    }

    public static function edit($data)
    {
        $params = [
            'facet_title'               => $data['facet_title'],
            'facet_description'         => $data['facet_description'],
            'facet_short_description'   => $data['facet_short_description'],
            'facet_info'                => $data['facet_info'],
            'facet_slug'                => $data['facet_slug'],
            'facet_seo_title'           => $data['facet_seo_title'],
            'facet_top_level'           => $data['facet_top_level'],
            'facet_user_id'             => $data['facet_user_id'],
            'facet_tl'                  => $data['facet_tl'],
            'facet_post_related'        => $data['facet_post_related'],
            'facet_id'                  => $data['facet_id'],
            'facet_is_web'              => $data['facet_is_web'],
            'facet_is_soft'             => $data['facet_is_soft'],
            'facet_type'                => $data['facet_type'],
        ];

        $sql = "UPDATE facets 
                    SET facet_title         = :facet_title,  
                    facet_description       = :facet_description, 
                    facet_short_description = :facet_short_description, 
                    facet_info              = :facet_info, 
                    facet_slug              = :facet_slug, 
                    facet_seo_title         = :facet_seo_title, 
                    facet_user_id           = :facet_user_id, 
                    facet_top_level         = :facet_top_level, 
                    facet_tl                = :facet_tl,                    
                    facet_post_related      = :facet_post_related, 
                    facet_is_web            = :facet_is_web,
                    facet_is_soft           = :facet_is_soft,
                    facet_type              = :facet_type
                        WHERE facet_id      = :facet_id";

        return  DB::run($sql, $params);
    }

    // Грани созданные участником
    // Faces created by the participant  
    /** 
     * @param  int $user_id
     * @param  string $type (topic | blog)
     */
    public static function getFacetsUser($user_id, $type)
    {
        $sql = "SELECT 
                    facet_id, 
                    facet_title, 
                    facet_slug, 
                    facet_user_id,
                    facet_type
                        FROM facets WHERE facet_user_id = :user_id AND facet_type = :type";

        return DB::run($sql, ['user_id' => $user_id, 'type' => $type])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество граней созданное участником
    // Number of faces created by the contributor 
    /** 
     * @param  int $user_id
     * @param  string $type (topic | blog)
     */
    public static function countFacetsUser($user_id, $type)
    {
        $sql = "SELECT 
                    facet_id, 
                    facet_title, 
                    facet_slug, 
                    facet_user_id,
                    facet_type
                        FROM facets WHERE facet_user_id = :user_id AND facet_type = :type";

        return DB::run($sql, ['user_id' => $user_id, 'type' => $type])->rowCount();
    }

    // Участники подписанные на тему
    // Participants subscribed to the topic
    /** 
     * @param  int $facet_id
     * @param  int $limit
     */
    public static function getFocusUsers($facet_id, $limit)
    {
        $sql = "SELECT 
                    signed_facet_id, 
                    signed_user_id,
                    user_id,
                    user_login,
                    user_avatar
                      FROM facets_signed 
                        LEFT JOIN users ON user_id = signed_user_id
                          WHERE signed_facet_id = :facet_id LIMIT $limit";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Вверх по структуре связанные деревья (РОДИТЕЛИ)
    // Up the structure of connected trees (PARENTS)
    /**
     * @param  int $facet_id
     * @return Response
     */
    public static function getHighMatching($facet_id)
    {
        $sql = "SELECT 
                    facet_id as value,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_is_web,
                    matching_chaid_id,
                    matching_parent_id
                        FROM facets  
                        LEFT JOIN facets_matching on facet_id = matching_parent_id
                        WHERE matching_chaid_id  = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Вниз по структуре связанных деревьев (ДЕТИ)
    // Down the structure  (CHILDREN)
    /**
     * @param  int $facet_id
     * @return
     */
    public static function getLowMatching($facet_id)
    {
        $sql = "SELECT 
                    facet_id as value,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_is_web,
                    matching_chaid_id,
                    matching_parent_id
                        FROM facets
                        LEFT JOIN facets_matching on facet_id = matching_chaid_id 
                        WHERE matching_parent_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Вверх по структуре основных деревьев (РОДИТЕЛИ)
    // Up the structure of the main trees (PARENTS)
    /**
     * @param  int $facet_id
     * @return
     */
    public static function getHighLevelList($facet_id)
    {
        $sql = "SELECT 
                    facet_id as value,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_is_web,
                    facet_chaid_id,
                    facet_parent_id
                        FROM facets  
                        LEFT JOIN facets_relation on facet_id = facet_parent_id
                        WHERE facet_chaid_id  = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Вниз по структуре основных деревьев (ДЕТИ)
    // Down the structure of the main trees (CHILDREN)
    /**
     * @param  int $facet_id
     * @internal
     */
    public static function getLowLevelList($facet_id)
    {
        $sql = "SELECT 
                    facet_id as value,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_is_web,
                    facet_chaid_id,
                    facet_parent_id
                        FROM facets
                        LEFT JOIN facets_relation on facet_id = facet_chaid_id 
                        WHERE facet_parent_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получение root уровня
    // Getting root level 
    public static function getTopLevelList()
    {
        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_top_level,
                    facet_is_web                    
                        FROM facets  
                            WHERE facet_top_level = 0";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Дерево тем
    // Theme Tree
    public static function getStructure()
    {
        $sql = "SELECT
                facet_id,
                facet_slug,
                facet_img,
                facet_title,
                facet_sort,
                facet_type,
                facet_parent_id,
                facet_chaid_id,
                rel.*
                    FROM facets 
                    LEFT JOIN
                    (
                        SELECT 
                            matching_parent_id,
                            GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS matching_list
                            FROM facets
                            LEFT JOIN facets_matching on facet_id = matching_chaid_id 
                            GROUP BY matching_parent_id
                        ) AS rel
                            ON rel.matching_parent_id = facet_id

                        LEFT JOIN facets_relation on facet_id = facet_chaid_id 
                            WHERE facet_type = 'topic' ORDER BY facet_sort DESC";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Посты где нет тем
    public static function getNoTopic()
    {
        $sql = "SELECT DISTINCT
                    post_id,
                    post_title,
                    post_slug,
                    post_draft,
                    post_is_deleted,
                    relation_post_id,
                    relation_facet_id
                        FROM posts
                            LEFT JOIN facets_posts_relation on relation_post_id = post_id
                            
                            WHERE relation_facet_id is NULL AND post_is_deleted = 0 AND post_draft = 0";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function ban($id, $status)
    {
        $sql = "UPDATE facets SET facet_is_deleted = 1 where facet_id = :id";
        if ($status == 1) {
            $sql = "UPDATE facets SET facet_is_deleted = 0 where facet_id = :id";
        }

        DB::run($sql, ['id' => $id]);
    }

    public static function setCover($content_id, $new_cover)
    {
        $params = [
            'facet_id'          => $content_id,
            'facet_cover_art'   => $new_cover,
        ];

        $sql = "UPDATE facets 
                    SET facet_cover_art = :facet_cover_art
                    WHERE facet_id = :facet_id";

        return  DB::run($sql, $params);
    }
}
