<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\FacetModel;

class RedirectController extends Controller
{
    public function index()
    {
        $facet_id  = Request::param('id')->asPositiveInt();
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
                $utl = url('home');
                break;
        }

        redirect($utl);
    }
}
