<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\FacetModel;

class RedirectController extends Controller
{
    public function index()
    {
        $facet_id  = Request::getInt('id');
        $facet = FacetModel::uniqueById($facet_id);

        switch ($facet['facet_type']) {
            case 'topic':
                $utl = url('topic', ['slug' => $facet['facet_slug']]);
                break;
            case 'blog':
                $utl = url('blog', ['slug' => $facet['facet_slug']]);
                break;
            case 'category':
                $utl = url('category', ['sort' => 'all', 'slug' => $facet['facet_slug']]);
                break;
            case 'section':
                $utl = url('admin.facets.type', ['type' => 'section']);
                break;
            default:
                $utl = '/';
                break;
        }

        redirect($utl);
    }
}
