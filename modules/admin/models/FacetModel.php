<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class FacetModel extends Model
{
    /**
     * Theme Tree
     * Дерево тем
     *
     * @param string $type
     * @param string $sort
     */
    public static function get(string $type, string $sort): false|array
    {
        $sort = $sort === 'ban' ? 'AND facet_is_deleted = 1' : '';

        $sql = "SELECT
                facet_id,
                facet_slug,
                facet_img,
                facet_title,
                facet_sort,
                facet_type,
                facet_parent_id,
                facet_chaid_id,
                facet_is_deleted,
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
                            WHERE facet_type = :type $sort ORDER BY facet_sort DESC";

        return DB::run($sql, ['type' => $type])->fetchAll();
    }

    public static function types()
    {
        return  DB::run('SELECT type_id, type_code, type_lang FROM facets_types');
    }

    /**
     * Posts where there are no topics
     * Посты где нет тем
     */
    public static function getNoTopic(): false|array
    {
        $sql = "SELECT DISTINCT
                    post_id,
                    post_title,
                    post_slug,
                    post_type
                        FROM posts
                            LEFT JOIN facets_posts_relation on relation_post_id = post_id
                                WHERE relation_facet_id is NULL AND post_draft = 0";

        return DB::run($sql)->fetchAll();
    }

    /**
     * Let's check the uniqueness of id
     * Проверим уникальность id
     *
     * @param integer $facet_id
     * @return mixed
     */
    public static function uniqueById(int $facet_id)
    {
        $sql = "SELECT facet_id, facet_slug, facet_type, facet_user_id, facet_is_deleted FROM facets WHERE facet_id = :id";

        return DB::run($sql, ['id' => $facet_id])->fetch();
    }

    /**
     * Delete (ban) the facet
     * Удалим (забаним) фасет
     *
     * @param integer $id
     * @param integer $status
     * @return void
     */
    public static function ban(int $id, int $status)
    {
        $sql = "UPDATE facets SET facet_is_deleted = 1 where facet_id = :id";
        if ($status == 1) {
            $sql = "UPDATE facets SET facet_is_deleted = 0 where facet_id = :id";
        }

        DB::run($sql, ['id' => $id]);
    }

    public static function getPostsTheSection(): false|array
    {
        $sql = "SELECT distinct (post_id),
                    post_title,
                    post_slug,
                    post_type,
                    post_is_deleted
                        FROM posts
                            LEFT JOIN facets_posts_relation on relation_post_id = post_id
                                WHERE relation_facet_id 
                                    AND post_type = 'page'";

        return DB::run($sql)->fetchAll();
    }
}
