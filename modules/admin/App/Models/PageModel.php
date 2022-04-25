<?php

namespace Modules\Admin\App\Models;

use DB;

class PageModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function get()
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
