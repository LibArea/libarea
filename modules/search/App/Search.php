<?php

namespace Modules\Search\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Search\App\Models\SearchModel;
use Modules\Search\App\Query\QueryBuilder;
use Modules\Search\App\Query\QuerySegment;
use Modules\Search\App\Engine;
use UserData, Meta;

class Search
{
    protected $limit = 10;

    public function __construct()
    {
        $this->engine = new Engine();
    }

    public function index()
    {
        return view(
            '/view/default/home',
            [
                'meta'  => Meta::get(__('search.title'), __('search.desc', ['name' => config('meta.name')])),
                'data'  => [
                    'type' => 'search',

                ]
            ]
        );
    }

    public function go()
    {
        $pageNumber = self::pageNumber(Request::getGetInt('page'));

        $q      = Request::getGet('q');
        $cat    = Request::getGet('cat');

        $allowed = ['post', 'website', 'all'];
        if (!in_array($cat, $allowed)) {
            $cat = 'all';
        }

        $sw = microtime(true);

        if ($q) {
            $segments = [];
            $isFacetSearching = false;

            /* TODO: If we introduce facets
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
            } */

            if ($cat != 'all') {
                $isFacetSearching = false;
                $segments =  QuerySegment::or(QuerySegment::exactSearch('cat', $cat));
            }

            $query = new QueryBuilder(QuerySegment::search($q, QuerySegment::and($segments)));
            $query->setLimit(Request::getGetInt('limit') == 0 ? 10 : Request::getGetInt('limit'));

            $start  = ($pageNumber - 1) * $this->limit;
            $query->setOffset($start);

            if (Request::getGet('connex') ?? false) $query->enableConnex();
            $facets = Request::getGet('facets');
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

            $count = $results['numFound'] ?? 0;
            self::setLogs(
                [
                    'request'       => $q,
                    'action_type'   => $cat,
                    'add_ip'        => Request::getRemoteAddress(),
                    'user_id'       => UserData::getUserId(),
                    'count_results' => $count ?? 0,
                ]
            );
        }

        $count = $results['numFound'] ?? 0;
        $facet = $cat == 'post' ? 'topic' : 'category';
        return view(
            '/view/default/search',
            [
                'meta'  => Meta::get(__('search')),
                'data'  => [
                    'results'       => $results ?? false,
                    'type'          => $cat,
                    'sheet'         => 'admin',
                    'q'             => $q,
                    'tags'          => self::searchTags($q, $facet, 4),
                    'sw'            => (microtime(true) - $sw ?? 0) * 1000,
                    'pagesCount'    => ceil($count / $this->limit),
                    'pNum'          => $pageNumber,
                ]
            ]
        );
    }

    public static function setLogs($params)
    {
        return SearchModel::setSearchLogs($params);
    }

    public static function getLogs($limit)
    {
        return SearchModel::getSearchLogs($limit);
    }

    public static function search($pageNumber, $limit, $query, $type)
    {
        return SearchModel::getSearch($pageNumber, $limit, $query, $type);
    }

    public static function searchTags($query, $type, $limit)
    {
        return SearchModel::getSearchTags($query,  $type, $limit);
    }

    public function api()
    {
        $query  = Request::getPost('query');
        $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $query);

        $topics = SearchModel::getSearchTags($search, 'topic', 3);
        $posts  = SearchModel::getSearch(1, 5, $search, 'post');
        $result = array_merge($topics, $posts);

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    public static function pageNumber($num)
    {
        return $num <= 1 ? 1 : $num;
    }
}
