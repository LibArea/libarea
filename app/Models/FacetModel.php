<?php

namespace App\Models;

use DB;

class FacetModel extends \Hleb\Scheme\App\Models\MainModel
{
    // All facets
    // Все фасеты
    public static function getFacetsAll($page, $limit, $uid, $sort)
    {
        switch ($sort) {
            case 'topics.my':
                $signet = "WHERE facet_type = 'topic' AND signed_user_id = $uid ORDER BY facet_count DESC";
                break;
            case 'topics.new':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_id DESC";
                break;
            case 'topics.all':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_count DESC";
                break;
            case 'topics.ban':
                $signet = "WHERE facet_type = 'topic' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            case 'blogs.all':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_count DESC";
                break;
            case 'blogs.new':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_id DESC";
                break;
            case 'blogs.my':
                $signet = "WHERE facet_type = 'blog' AND signed_user_id = $uid ORDER BY facet_user_id = $uid DESC, facet_count DESC";
                break;
            case 'blogs.ban':
                $signet = "WHERE facet_type = 'blog' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            case 'sections.all':
                $signet = "WHERE facet_type = 'section' ORDER BY facet_count DESC";
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
                        LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = $uid
                        $signet
                        LIMIT $start, $limit";

        return DB::run($sql)->fetchAll();
    }

    public static function getFacetsAllCount($uid, $sort)
    {
        switch ($sort) {
            case 'topics.my':
                $signet = "WHERE facet_type = 'topic' AND signed_user_id = $uid ORDER BY facet_count DESC";
                break;
            case 'topics.new':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_id DESC";
                break;
            case 'topics.all':
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_id DESC";
                break;
            case 'topics.ban':
                $signet = "WHERE facet_type = 'topic' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            case 'blogs.all':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_count DESC";
                break;
            case 'blogs.new':
                $signet = "WHERE facet_type = 'blog' ORDER BY facet_id DESC";
                break;
            case 'blogs.my':
                $signet = "WHERE facet_type = 'blog' AND signed_user_id = $uid ORDER BY facet_count DESC";
                break;
            case 'blogs.ban':
                $signet = "WHERE facet_type = 'blog' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            case 'sections.all':
                $signet = "WHERE facet_type = 'section' ORDER BY facet_count DESC";
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
                        LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = $uid
                        $signet";

        return DB::run($sql)->rowCount();
    }

    // Cell information (id, slug) 
    // Информация по фасету (id, slug)
    public static function getFacet($params, $name, $type)
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
                    facet_type,
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
                    facet_is_deleted
                        FROM facets WHERE $sort AND facet_type = :type";

        return DB::run($sql, ['params' => $params, 'type' => $type])->fetch();
    }

    // Let's check the uniqueness of slug depending on the type of tree
    // Проверим уникальность slug в зависимости от типа дерева
    public static function uniqueSlug($facet_slug, $facet_type)
    {
        $sql = "SELECT facet_slug, facet_type FROM facets WHERE facet_slug = :slug AND facet_type = :type";
        
        return DB::run($sql, ['slug' => $facet_slug, 'type' => $facet_type])->fetch();
    }

    // Let's check the uniqueness of id
    // Проверим уникальность id
    public static function uniqueById($facet_id)
    {
        $sql = "SELECT facet_id, facet_slug, facet_type, facet_user_id FROM facets WHERE facet_id = :id";
        
        return DB::run($sql, ['id' => $facet_id])->fetch();
    }

    // Facet owner 
    // Собственник фасета
    public static function getOwnerFacet($uid, $type)
    {
        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_description,
                    facet_short_description,
                    facet_type,
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
                    facet_is_deleted
                        FROM facets 
                            WHERE facet_type = :type AND facet_user_id = :uid AND facet_is_deleted = 0";

        return DB::run($sql, ['uid' => $uid, 'type' => $type])->fetchAll();
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
            if ($item_id == $row['id']) return true;
                $sql = "INSERT INTO facets_items_relation (relation_facet_id, relation_item_id) 
                        VALUES ($facet_id, $item_id)";

                DB::run($sql);
        }

        return true;
    }

    // Main trees
    // Основные деревья
    public static function addLowFacetRelation($rows, $topic_id)
    {
        self::deleteRelation($topic_id, 'topic');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            if ($topic_id == $row['id']) return true;
                $sql = "INSERT INTO facets_relation (facet_parent_id, facet_chaid_id) 
                        VALUES ($topic_id, $facet_id)";

                DB::run($sql);
        }

        return true;
    }

    // Cross -links
    // Перекрестные связи
    public static function addLowFacetMatching($rows, $topic_id)
    {
        self::deleteRelation($topic_id, 'matching');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            if ($topic_id == $row['id']) return true;
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

    // Changing img
    // Изменение img
    public static function setImg($params)
    {
        $sql = "UPDATE facets SET facet_img = :facet_img WHERE facet_id = :facet_id";

        return DB::run($sql, $params);
    }

    // TOP of facet authors.
    // TOP авторов фасета.
    public static function getWriters($facet_id, $limit)
    {
        $sql = "SELECT SUM(post_hits_count) as hits_count, 
                    rel.*
                        FROM facets_posts_relation 
                        LEFT JOIN posts ON relation_post_id = post_id 
                        LEFT JOIN
                        (
                            SELECT 
                                id,
                                login,                                 
                                avatar,
                                about
                                    FROM users 
                        ) AS rel
                            ON rel.id = post_user_id
                 WHERE relation_facet_id = :facet_id  GROUP BY post_user_id 
                 ORDER BY hits_count DESC LIMIT $limit";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Participant contribution
    // Вклад участника
    public static function participation($uid)
    {
        $sql = " SELECT 
                    relation_facet_id as count,
                    rel.*
                    FROM posts
                    LEFT JOIN facets_posts_relation ON relation_post_id = post_id
                    LEFT JOIN
                        ( SELECT 
                            facet_id,
                            facet_slug,
                            facet_type,
                            facet_title
                                FROM facets
                        ) AS rel
                            ON rel.facet_id = relation_facet_id
                                WHERE post_user_id = :uid AND facet_type = 'topic'
                                    GROUP BY relation_facet_id 
                                        ORDER BY count DESC LIMIT 5";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    public static function userReads($uid)
    {
        $sql = "SELECT 
                    signed_facet_id                  
                        FROM facets_signed 
                            WHERE signed_user_id = :uid";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    public static function advice($uid)
    {
        if ($uid == 0) return true;

        $userReads = self::userReads($uid);
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

        return DB::run($sql)->fetchAll();
    }

    public static function add($params)
    {
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

        return  DB::run("SELECT LAST_INSERT_ID() as facet_id")->fetch();
    }

    public static function edit($params)
    {
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
                    facet_type              = :facet_type
                        WHERE facet_id      = :facet_id";

        return  DB::run($sql, $params);
    }

    // Faces created by the participant 
    // Грани созданные участником
    /** 
     * @param  int $user_id
     * @param  string $type (topic | blog)
     */
    public static function getFacetsUser($uid, $type)
    {
        $sql = "SELECT 
                    facet_id, 
                    facet_title, 
                    facet_slug, 
                    facet_user_id,
                    facet_type
                        FROM facets WHERE facet_user_id = :uid AND facet_type = :type";

        return DB::run($sql, ['uid' => $uid, 'type' => $type])->fetchAll();
    }

    // Number of faces created by the contributor
    // Количество граней созданное участником
    /** 
     * @param  int $user_id
     * @param  string $type (topic | blog)
     */
    public static function countFacetsUser($uid, $type)
    {
        $sql = "SELECT 
                    facet_id, 
                    facet_title, 
                    facet_slug, 
                    facet_user_id,
                    facet_type
                        FROM facets WHERE facet_user_id = :uid AND facet_type = :type";

        return DB::run($sql, ['uid' => $uid, 'type' => $type])->rowCount();
    }

    // Participants subscribed to the topic
    // Участники подписанные на тему
    /** 
     * @param  int $facet_id
     * @param  int $limit
     */
    public static function getFocusUsers($facet_id, $limit)
    {
        $sql = "SELECT 
                    signed_facet_id, 
                    signed_user_id,
                    id,
                    login,
                    avatar
                      FROM facets_signed 
                        LEFT JOIN users ON id = signed_user_id
                          WHERE signed_facet_id = :facet_id LIMIT $limit";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Up the structure of connected trees (PARENTS)
    // Вверх по структуре связанные деревья (РОДИТЕЛИ)
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
                    facet_type,
                    matching_chaid_id,
                    matching_parent_id
                        FROM facets  
                        LEFT JOIN facets_matching on facet_id = matching_parent_id
                        WHERE matching_chaid_id  = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Down the structure  (CHILDREN)
    // Вниз по структуре связанных деревьев (ДЕТИ)
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
                    facet_type,
                    matching_chaid_id,
                    matching_parent_id
                        FROM facets
                        LEFT JOIN facets_matching on facet_id = matching_chaid_id 
                        WHERE matching_parent_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Up the structure of the main trees (PARENTS)
    // Вверх по структуре основных деревьев (РОДИТЕЛИ)
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
                    facet_type,
                    facet_chaid_id,
                    facet_parent_id
                        FROM facets  
                        LEFT JOIN facets_relation on facet_id = facet_parent_id
                        WHERE facet_chaid_id  = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Down the structure of the main trees (CHILDREN)
    // Вниз по структуре основных деревьев (ДЕТИ)
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
                    facet_type,
                    facet_chaid_id,
                    facet_parent_id
                        FROM facets
                        LEFT JOIN facets_relation on facet_id = facet_chaid_id 
                        WHERE facet_parent_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Getting root level 
    // Получение root уровня
    public static function getTopLevelList()
    {
        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_top_level,
                    facet_type                    
                        FROM facets  
                            WHERE facet_top_level = 0";

        return DB::run($sql)->fetchAll();
    }

    // Posts where there are no topics
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

        return DB::run($sql)->fetchAll();
    }

    public static function ban($id, $status)
    {
        $sql = "UPDATE facets SET facet_is_deleted = 1 where facet_id = :id";
        if ($status == 1) {
            $sql = "UPDATE facets SET facet_is_deleted = 0 where facet_id = :id";
        }

        DB::run($sql, ['id' => $id]);
    }

    public static function setCover($params)
    {
        $sql = "UPDATE facets 
                    SET facet_cover_art = :facet_cover_art
                        WHERE facet_id  = :facet_id";

        return  DB::run($sql, $params);
    }
    
    public static function types()
    {
        return  DB::run('SELECT type_id, type_code, type_lang FROM facets_types');
    }
}
