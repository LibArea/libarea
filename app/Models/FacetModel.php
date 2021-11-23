<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class FacetModel extends MainModel
{
    // Все темы
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
            case 'admin.blogs.ban.all':
                $signet = "WHERE facet_type = 'blog' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
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

    // Количество
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
            case 'admin.blogs.ban.all':
                $signet = "WHERE facet_type = 'blog' AND facet_is_deleted = 1 ORDER BY facet_id DESC";
                break;
            default:
                $signet = "WHERE facet_type = 'topic' ORDER BY facet_count DESC";
                break;
        }

        $sql    = "SELECT 
                    facet_id,
                    facet_type,
                    signed_facet_id, 
                    signed_user_id
                        FROM facets 
                        LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = :user_id
                        $signet";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    // Информация по фасету (id, slug)
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
                    facet_add_date,
                    facet_seo_title,
                    facet_merged_id,
                    facet_top_level,
                    facet_user_id,
                    facet_tl,
                    facet_related,
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
            $sql = "INSERT INTO facets_posts_relation (relation_facet_id, relation_post_id) 
                        VALUES ($row[0], $row[1])";

            DB::run($sql);
        }

        return true;
    }

    public static function addLinkFacets($rows, $link_id)
    {
        self::deleteRelation($link_id, 'link');

        foreach ($rows as $row) {
            $sql = "INSERT INTO facets_links_relation (relation_facet_id, relation_link_id) 
                        VALUES ($row[0], $row[1])";

            DB::run($sql);
        }

        return true;
    }

    public static function deleteRelation($id, $type)
    {
        $sql = "DELETE FROM facets_links_relation WHERE relation_link_id =  $id";
        if ($type == 'post') {
            $sql = "DELETE FROM facets_posts_relation WHERE relation_post_id =  $id";
        }

        return DB::run($sql);
    }

    // Изменение img
    public static function setImg($facet_id, $img)
    {
        $sql = "UPDATE facets SET facet_img = :img WHERE facet_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id, 'img' => $img]);
    }

    // Связанные темы
    public static function facetRelated($topic_related)
    {
        $string = "facet_id IN(0)";
        if ($topic_related) {
            $string = "facet_id IN(0, " . $topic_related . ")";
        }

        $sql = "SELECT facet_id, facet_title, facet_slug, facet_img, facet_is_web FROM facets WHERE $string ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Связанные посты для детальной информации по теме
    public static function relatedPosts($facet_post_related)
    {
        $sql = "SELECT post_id, post_title, post_slug FROM posts WHERE post_id IN(0, " . $facet_post_related . ") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // TOP авторов темы. Limit 10
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
            'facet_related'             => $data['facet_related'],
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
                    facet_related           = :facet_related,
                    facet_is_web            = :facet_is_web,
                    facet_is_soft           = :facet_is_soft,
                    facet_type              = :facet_type
                        WHERE facet_id      = :facet_id";

        return  DB::run($sql, $params);
    }


    // Грани созданные участником
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

    // Вниз по структуре
    public static function getLowLevelList($facet_id)
    {
        $sql = "SELECT 
                    facet_id,
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

    // Вверх по структуре
    public static function getHighLevelList($facet_id)
    {
        $sql = "SELECT 
                    facet_id,
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

    // Получение root уровня
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

    // Удалить привязку темы к другой теме
    public static function deleteFacetRelation($facet_id)
    {
        $sql = "DELETE FROM facets_relation WHERE facet_chaid_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id]);
    }

    public static function addFacetRelation($rows, $facet_id)
    {
        self::deleteFacetRelation($facet_id);

        foreach ($rows as $row) {
            $sql = "INSERT INTO facets_relation (facet_parent_id, facet_chaid_id) 
                        VALUES ($row[0], $row[1])";

            DB::run($sql);
        }

        return true;
    }

    // Дерево тем
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
                facet_chaid_id
                    FROM facets 
                        LEFT JOIN facets_relation on facet_id = facet_chaid_id
                        WHERE facet_type = 'topic' ORDER BY facet_sort DESC";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Поиск для родительской темы
    public static function getSearchParent($search, $facet_id)
    {
        $field_tl = 'facet_tl';
        $sql = "SELECT facet_id, facet_title, facet_tl FROM facets 
                    WHERE facet_title LIKE :facet_title AND facet_id != :facet_id
                       ORDER BY facet_count DESC LIMIT 8";

        $result = DB::run($sql, ['facet_title' => "%" . $search . "%", 'facet_id' => $facet_id]);
        $lists  = $result->fetchall(PDO::FETCH_ASSOC);

        $response = [];
        foreach ($lists as $list) {
            $response[] = array(
                "id"    => $list['facet_id'],
                "text"  => $list['facet_title'],
                "tl"    => $list['facet_tl']
            );
        }

        return json_encode($response);
    }
}
