<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\FacetModel;
use Meta, UserData;

class TeamFacetController extends Controller
{
    public function index()
    {
        if (!in_array($type = Request::get('type'), ['topic', 'blog', 'category', 'section'])) {
            self::error404($facet);
        }

        $facet_id   = Request::getInt('id');        
        $facet      = FacetModel::getFacet($facet_id, 'id', $type);
        self::error404($facet);
 
        return $this->render(
            '/facets/team',
            [
                'meta'  => Meta::get(__('app.team') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'facet' => $facet,
                    'sheet' => $facet['facet_type'] . 's',
                    'type'  => $type,
                ]
            ]
        );  
    }
}
