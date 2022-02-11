<?php

namespace Modules\Admin\App;

use Modules\Admin\App\Models\FacetModel;
use Translate, Tpl;

class TreeFacet
{
    public function index($type)
    {
        return view(
            '/view/default/facet/structure',
            [
                'meta'  => meta($m = [], Translate::get('structure'), Translate::get('structure-desc')),
                'data'  => [
                    'sheet'         => 'structure',
                    'type'          => $type,
                    'structure'     => self::builder(0, 0, FacetModel::get($type)),
                    'types_facets'  => FacetModel::types(),
                ]
            ]
        );
    }

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
}
