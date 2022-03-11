<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\App\Models\{FacetModel, StatsModel};
use Translate;

class Facets
{
    // Let's show the types of facets
    // Покажем типы фасетов
    public function index()
    {
        return view(
            '/view/default/facet/all',
            [
                'meta'  => meta($m = [], Translate::get('facets'), Translate::get('facets')),
                'data'  => [
                    'count'         => StatsModel::getCount(),
                    'sheet'         => 'all',
                    'type'          => 'facets',
                    'types_facets'  => FacetModel::types(),
                ]
            ]
        );
    }

    // Show faces by type 
    // Покажем грани по типам
    public function type()
    {
        $type = self::faceTypes(Request::get('type'));

        return view(
            '/view/default/facet/type',
            [
                'meta'  => meta($m = [], Translate::get($type), Translate::get('facets')),
                'data'  => [
                    'count'     => StatsModel::getCount(),
                    'sheet'     => $type,
                    'type'      => $type,
                    'facets'    => self::builder(0, 0, FacetModel::get($type, 'all')),
                ]
            ]
        );
    }

    // Building a tree
    // Дерево
    public static function builder($chaid_id, $level, $data, array $tree = [])
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

    // Deleted Faces 
    // Удаленные грани
    public function ban()
    {
        $type = self::faceTypes(Request::get('type'));

        return view(
            '/view/default/facet/type',
            [
                'meta'  => meta($m = [], Translate::get('ban'), Translate::get('ban')),
                'data'  => [
                    'sheet'     => 'ban.facet',
                    'type'      => $type,
                    'facets'    => FacetModel::get($type, 'ban'),
                ]
            ]
        );
    }

    // Remove Facet  
    // Удалим фасет
    public function deletes()
    {
        $id = Request::getPostInt('id');

        $topic = FacetModel::uniqueById($id);
        FacetModel::ban($id, $topic['facet_is_deleted']);

        return true;
    }

    // Check for allowed face types    
    // Проверка на разрешенные типы граней
    public static function faceTypes($type)
    {
        $allowed = ['topic', 'blog', 'category', 'section'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        return $type;
    }
}
