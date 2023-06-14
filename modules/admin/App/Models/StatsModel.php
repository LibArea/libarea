<?php

namespace Modules\Admin\App\Models;

use DB;

class StatsModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Страница аудита
    public static function getCount()
    {
        $sql = "SELECT 
                    (SELECT COUNT(facet_id) 
                        FROM facets WHERE facet_type = 'topic') 
                            AS count_topic,

                    (SELECT COUNT(facet_id) 
                        FROM facets WHERE facet_type = 'section') 
                            AS count_section,

                    (SELECT COUNT(facet_id) 
                        FROM facets WHERE facet_type = 'category') 
                            AS count_category,

(                   SELECT COUNT(facet_id) 
                        FROM facets WHERE facet_type = 'blog') 
                            AS count_blog";

        return DB::run($sql)->fetch();
    }
}
