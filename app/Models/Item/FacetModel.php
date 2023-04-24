<?php

namespace App\Models\Item;

use DB;

class FacetModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Cell information (id, slug) 
    // Информация по фасету (id, slug)
    public static function get($params, $name, $trust_level)
    {
        // Except for the staff and if it is not allowed in the catalog
        // Кроме персонала и если он не разрешен в каталоге
        $display = 'facet_is_deleted = 0 AND';
        if ($trust_level == 10) $display = '';

        $sort = "facet_id = :params";
        if ($name == 'slug') $sort = "facet_slug = :params";


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
                    facet_date,
                    facet_seo_title,
                    facet_merged_id,
                    facet_top_level,
                    facet_user_id,
                    facet_tl,
                    facet_post_related,
                    facet_focus_count,
                    facet_count,
                    facet_is_deleted
                        FROM facets WHERE facet_type='category' AND $display $sort";

        return DB::run($sql, ['params' => $params])->fetch();
    }


    // Getting subcategories based on nested sites 
    // Получаем подкатегории с учетов вложенных сайтов 
    /**
     * @param  int $facet_id
     * @return
     */
    public static function getChildrens($facet_id, $screening)
    {
        $go = '';
        $os = config('catalog/items.type');
        if (in_array($screening, $os)) {
            $go = "AND item_is_" . $screening . " = 1";
        }

        $sql = "SELECT 
                  facet_id,
                  count(facet_id) as counts, 
                  MAX(facet_title) as facet_title, 
                  MAX(facet_slug) as facet_slug
                      FROM facets_relation 
                          LEFT JOIN facets on facet_id = facet_chaid_id 
                          LEFT JOIN facets_items_relation on facet_chaid_id = relation_facet_id 
                          LEFT JOIN items on item_id = relation_item_id 
                              WHERE facet_parent_id = :facet_id  $go
                                 GROUP BY facet_id";

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
                    facet_id,
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
}
