<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class StatsModel extends Model
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
