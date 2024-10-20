<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\FacetModel;

class RedirectController extends Controller
{
    public function index(): void
    {
        $facet_id  = Request::param('id')->asPositiveInt();
        $facet = FacetModel::uniqueById($facet_id);

		$utl = match ($facet['facet_type']) {
			'topic'		=> url('topic', ['slug' => $facet['facet_slug']]),
			'blog'		=> url('blog', ['slug' => $facet['facet_slug']]),
			'category'	=> url('category', ['sort' => 'all', 'slug' => $facet['facet_slug']]),
			'section'	=> url('admin.facets.type', ['type' => 'section']),
			default		=> url('home'),
		};

        redirect($utl);
    }
}
