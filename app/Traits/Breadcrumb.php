<?php

namespace App\Traits;

use App\Models\FacetModel;

trait Breadcrumb
{
    public static function get($facet_id, $sort = [], $type = 'category')
    {
        $facet = FacetModel::breadcrumb($facet_id);

        return self::breadcrumb($facet, $sort, $type);
    }

    public static function breadcrumb($facet, $sort, $type)
    {
        // Now we can expand if other types are needed
        $arr = ($type == 'category') ? [['name' => __('web.catalog'), 'link' => url('web')]] : [];

        $result = [];
        foreach ($facet as $row) {
            
            // Route::get('/web/{grouping}/dir/{sort}/{slug}')
            $url = ($type == 'category') ? url('category', ['sort' => $sort, 'slug' => $row['link']]) : [];
            
            $result[] = ["name" => $row['name'], "link" => $url];
        }

        return array_merge($arr, $result);
    }

}
