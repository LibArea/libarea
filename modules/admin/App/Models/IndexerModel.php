<?php

namespace Modules\Admin\App\Models;

use DB;

class IndexerModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function getItems()
    {
        return DB::run("SELECT item_id, item_title, item_content, item_url, item_domain FROM items WHERE item_is_deleted = 0")->fetchAll();
    }

    public static function getPosts()
    {
        return DB::run("SELECT post_id, post_title, post_content FROM posts WHERE post_is_deleted = 0")->fetchAll();
    }
}
