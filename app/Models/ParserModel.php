<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class ParserModel extends Model
{
    // Member information (id, slug) for @nickname
    // Информация по участнику (id, slug) для @nickname
    public static function getUser($params, $type)
    {
        $field = ($type === 'slug') ? "login" : "id";

        $sql = "SELECT id, login FROM users WHERE $field = :params AND activated = 1 AND is_deleted = 0";

        return DB::run($sql, ['params' => $params])->fetch();
    }

    // Facet information based on type (topic, category, section)
    // Информация по фасету с учетом типа (тема, категория, раздел)
    public static function getFacet($slug, $type = 'topic')
    {
        $sql = "SELECT facet_title, facet_slug, facet_img FROM facets WHERE facet_slug = :slug AND facet_type = :type";

        return DB::run($sql, ['slug' => $slug, 'type' => $type])->fetch();
    }
}
