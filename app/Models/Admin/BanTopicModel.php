<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;

class BanTopicModel extends MainModel
{
    public static function setBan($id, $status)
    {
        $sql = "UPDATE facets SET facet_is_deleted = 1 where facet_id = :id";
        if ($status == 1) {
            $sql = "UPDATE facets SET facet_is_deleted = 0 where facet_id = :id";
        }

        DB::run($sql, ['id' => $id]);
    }
}
