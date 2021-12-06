<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\FacetModel;
use Base, Translate;

class TreeFacetController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = Base::getUid();
    }

    public function index()
    {
        return view(
            '/facets/structure',
            [
                'meta'  => meta($m = [], Translate::get('structure'), Translate::get('structure-desc')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'     => 'structure',
                    'structure' => self::builder(0, 0, FacetModel::getStructure()),
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
