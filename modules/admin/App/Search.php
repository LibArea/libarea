<?php


namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Search\App\Query\QueryBuilder;
use Modules\Search\App\Query\QuerySegment;
use Modules\Search\App\Engine;
use Meta, UserData;

class Search
{
    private $user;

    private $engine;

    public function __construct()
    {
        $this->user  = UserData::get();
        $this->engine = new Engine([]);
    }

    public function index()
    {
        $engine = new Engine();
        $stats = $engine->getIndex()->getStats();

        return view(
            '/view/default/search/admin',
            [
                'meta'  => Meta::get(__('search')),
                'user'  => $this->user,
                'data'  => [
                    'type'          => 'statistics',
                    'sheet'         => 'statistics',
                    'stats'         => $stats,

                ]
            ]
        );
    }


    /**
     * Route : /query
     * Methods : GET
     * Parameters :
     *     'q' : current query
     *     'limit' : maximum document count
     *     'offset' :  offset the results by this (for pagination purposes)
     *     'facets' : Display listed facets (comma separated)
     *     'connex' : Enable Connex Search
     * @throws Exception
     */
    public  function query()
    {
        $q      = Request::getGet('q');
        $cat    = Request::getGet('cat') ?? 'post';
        $cat    = $cat == 'post' ? 'post' : 'website';

        $sw = microtime(true);
        $segments = [];
        $isFacetSearching = false;
        foreach (Request::getGet() as $field => $value) {
            if (strpos($field, 'facet-') === 0) {
                $isFacetSearching = true;
                $facetField = substr($field, 6);
                $subSeg = [];
                foreach ($value as $v) {
                    $subSeg[] = QuerySegment::exactSearch($facetField, $v);
                }
                $segments[] = QuerySegment::or($subSeg);
            }
        }

        if ($cat) {
            $isFacetSearching = false;
            $segments =  QuerySegment::or(QuerySegment::exactSearch('cat', $cat));
        }

        $query = new QueryBuilder(QuerySegment::search($q, QuerySegment::and($segments)));

        $query->setLimit(Request::getGetInt('limit') ?? 10);
        $query->setOffset(Request::getGetInt('offset') ?? 0);

        if (Request::getGet('connex') ?? false) $query->enableConnex();
        $facets = Request::getGet('facets') ?? '';
        if (!empty($facets)) {
            foreach (explode(',', $facets) as $facet) {
                $query->addFacet($facet);
            }
        }

        if ($isFacetSearching) {
            $results = $this->engine->search($q, $query->getFilters());
        } else {
            $results = $this->engine->search($query);
        }

        $sw = (microtime(true) - $sw) * 1000;

        return view(
            '/view/default/search/query',
            [
                'meta'  => Meta::get(__('search')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'query',
                    'sheet'     => 'query',
                    'sw'        => $sw,
                    'results'   => $results,

                ]
            ]
        );
    }

    public function schemas()
    {
        $schemas = $this->engine->getIndex()->getSchemas();
        return view(
            '/view/default/search/schemas',
            [
                'meta'  => Meta::get(__('search')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'schemas',
                    'sheet'     => 'schemas',
                    'schemas'   => $schemas,

                ]
            ]
        );
    }
}
