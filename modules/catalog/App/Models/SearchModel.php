<?php

namespace Modules\Catalog\App\Models;

use DB;

class SearchModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function getSearch($page, $limit, $query)
    {
        $start  = ($page - 1) * $limit;

        $sql = "SELECT DISTINCT 
                item_id, 
                item_title_url as title, 
                item_content_url as content,
                item_url,
                item_url_domain,
                item_votes,
                item_count,
                rel.*
                    FROM facets_items_relation  
                    LEFT JOIN items ON relation_item_id = item_id 
                    LEFT JOIN ( SELECT  
                            relation_item_id,  
                            GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list  
                            FROM facets  
                            LEFT JOIN facets_items_relation on facet_id = relation_facet_id  
                                GROUP BY relation_item_id  
                    ) AS rel ON rel.relation_item_id = item_id  
                            WHERE item_is_deleted = 0
                                AND MATCH(item_title_url, item_content_url, item_url_domain) AGAINST (:qa)
                                          LIMIT $start, $limit";

        return DB::run($sql, ['qa' => $query])->fetchall();
    }

    public static function getSearchCount($query)
    {
        $sql = "SELECT DISTINCT 
                  item_id
                    FROM items
                            WHERE item_is_deleted = 0
                             AND MATCH(item_title_url, item_content_url, item_url_domain) AGAINST (:qa)";

        return DB::run($sql, ['qa' => "%" . $query . "%"])->rowCount();
    }


    public static function getSearchTags($query, $limit)
    {
        $sql = "SELECT 
                    facet_slug, 
                    facet_count, 
                    facet_title,
                    facet_is_web,
                    facet_img
                        FROM facets WHERE facet_title LIKE :qa1 OR facet_slug LIKE :qa2 
                           LIMIT $limit";

        return DB::run($sql, ['qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%"])->fetchAll();
    }
}
