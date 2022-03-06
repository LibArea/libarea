<?php

namespace Modules\Admin\App\Models;

use DB;

class FacetModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Theme Tree
    // Дерево тем
    public static function get($type)
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
                            WHERE facet_type = :type ORDER BY facet_sort DESC";

        return DB::run($sql, ['type' => $type])->fetchAll();
    }

    public static function types()
    {
        return  DB::run('SELECT type_id, type_code, type_lang FROM facets_types');
    }
    
    // Posts where there are no topics
    // Посты где нет тем
    public static function getNoTopic()
    {
        $sql = "SELECT DISTINCT
                    post_id,
                    post_title,
                    post_slug
                        FROM posts
                            LEFT JOIN facets_posts_relation on relation_post_id = post_id
                                WHERE relation_facet_id is NULL 
                                    AND post_type = 'post'
                                       AND post_is_deleted = 0 AND post_draft = 0";

        return DB::run($sql)->fetchAll();
    }
}
