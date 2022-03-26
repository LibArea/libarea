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
    
    // Страница аудита
    public static function getReplys($limit)
    {
        $sql = "SELECT 
                    reply_id,
                    reply_user_id,                
                    reply_item_id,
                    reply_parent_id,
                    reply_content as content,
                    reply_date as date,
                    item_domain,
                    id, 
                    login, 
                    avatar
                        FROM replys 
                          LEFT JOIN users ON id = reply_user_id
                          LEFT JOIN items ON item_id = reply_item_id
                              ORDER BY reply_id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }
}
