<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Modules\Admin\Models\{StatsModel, FacetModel};
use Meta;

class FacetsController extends Module
{
    /**
     * Let's show the types of facets
     * Покажем типы фасетов
     */
    public function index(): View
    {
        return view(
            'facet/all',
            [
                'meta'  => Meta::get(__('admin.facets')),
                'data'  => [
                    'count'         => StatsModel::getCount(),
                    'sheet'         => 'all',
                    'type'          => 'facets',
                    'types_facets'  => FacetModel::types(),
                ]
            ]
        );
    }

    /**
     * Show faces by type
     * Покажем грани по типам
     */
    public function type(): View
    {
        $type = self::faceTypes(Request::param('type')->asString());

        $pages = $type === 'section' ? FacetModel::getPostsTheSection() : false;

        return view(
            '/facet/type',
            [
                'meta'  => Meta::get(__('admin.' . $type)),
                'data'  => [
                    'count'     => StatsModel::getCount(),
                    'sheet'     => $type,
                    'type'      => $type,
                    'pages'     => $pages,
                    'facets'    => self::builder(0, 0, FacetModel::get($type, 'all')),
                ]
            ]
        );
    }

    /**
     * Building a tree
     * Дерево
     * 
     *
     * @param int $chaid_id
     * @param int $level
     * @param array $data
     * @param array $tree
     */
    public static function builder(int $chaid_id, int $level, array $data, array $tree = []): array
    {
        $level++;
        foreach ($data as $part) {
            if ($part['facet_parent_id'] == $chaid_id) {
                $part['level']  = $level - 1;
                $tree[]         = $part;
                $tree           = self::builder($part['facet_id'], $level, $data, $tree);
            }
        }
        return $tree;
    }

    /**
     * Deleted Faces 
     * Удаленные грани
     */
    public function ban(): View
    {
        $type = self::faceTypes(Request::param('type')->asString());

        return view(
            '/facet/type',
            [
                'meta'  => Meta::get(__('admin.ban')),
                'data'  => [
                    'sheet'     => 'ban.facet',
                    'type'      => $type,
                    'facets'    => FacetModel::get($type, 'ban'),
                ]
            ]
        );
    }

    /**
     * Remove Facet 
     * Удалим фасет
     */
    public function deletes(): true
    {
        $id = Request::post('id')->asInt();

        $topic = FacetModel::uniqueById($id);
        FacetModel::ban($id, $topic['facet_is_deleted']);

        return true;
    }

    /**
     * Check for allowed face types
     * Проверка на разрешенные типы граней
     *
     * @param string $type
     * @return false|string
     */
    public static function faceTypes(string $type): false|string
    {
        $allowed = ['topic', 'blog', 'category', 'section'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        return $type;
    }
}
