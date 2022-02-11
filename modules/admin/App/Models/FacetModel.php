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
}
