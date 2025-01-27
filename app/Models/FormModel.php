<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class FormModel extends Model
{
    /**
     * Find content for forms
     * Поиск контента для форм
     *
     * @param null|string $search
     * @param string $type
     */
    public static function get(null|string $search, string $type)
    {
        $field_id   = $type . '_id';
        $field_name = '';
        $sql = '';

        switch ($type) {
            case 'post':
                $field_name = 'post_title';
                $sql = "SELECT post_id, post_title FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_hidden = 0 AND post_tl = 0 AND post_type = 'post' ORDER BY post_id DESC LIMIT 500";
                break;

            case 'user':
            case 'team':
                $field_id = 'id';
                $field_name = 'login';
                $sql = "SELECT id, login, trust_level, activated FROM users WHERE activated = 1 AND login LIKE :login";
                if ($type == 'team') {
                    $sql .= " AND id !=" . self::container()->user()->id();
                }
                break;

            case 'poll':
                $field_name = 'poll_title';
                $sql = "SELECT poll_id, poll_title FROM polls WHERE poll_title LIKE :poll_title AND poll_is_deleted = 0";

                if (!self::container()->user()->admin()) {
                    $sql .= " AND poll_user_id = " . self::container()->user()->id();
                }
                break;

            default:
                $field_id = 'facet_id';
                $field_name = 'facet_title';
                $condition = '';

                if (!self::container()->user()->admin() && $type == 'blog') {
                    $blog = FacetModel::getFacetsUser('blog');
                    $teams = FacetModel::getTeamFacets('blog');

                    $resultUsers = [];
                    foreach (array_merge($teams, $blog) as $ind => $row) {
                        $resultUsers[$ind] = $row['facet_id'];
                    }

                    $condition =  "AND facet_id IN(" . implode(',', $resultUsers ?? []) . ")";
                }

                $tl = self::container()->user()->tl();
                $sql = "SELECT facet_id, facet_title, facet_type FROM facets 
					WHERE facet_title LIKE :facet_title AND facet_tl <= $tl AND facet_is_deleted = 0 AND facet_type = '$type' $condition ORDER BY facet_count DESC LIMIT 200";
                break;
        }

        $lists = DB::run($sql, [$field_name => "%" . $search . "%"])->fetchAll();

        $response = [];
        foreach ($lists as $list) {
            $response[] = array(
                "id"    => $list[$field_id],
                "value" => $list[$field_name],
            );
        }

        return json_encode($response);
    }
}
