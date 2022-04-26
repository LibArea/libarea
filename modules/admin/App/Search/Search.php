<?php


namespace Modules\Admin\App\Search;

use Hleb\Constructor\Handlers\Request;
use Modules\Search\App\Query\QueryBuilder;
use Modules\Search\App\Query\QuerySegment;
use Modules\Admin\App\Search\Actions;
use Modules\Search\App\Engine;
use Meta, UserData;

class Search
{
    private $user;

    private $engine;
    
    private $actions;

    public function __construct()
    {
        $this->user  = UserData::get();
        $this->engine = new Engine();
        $this->actions = new Actions();
    }

    public function index()
    {
        $stats = $this->engine->getIndex()->getStats();

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

    public function editForm()
    {
        $errors = [];
        $id = Request::getInt('id') ?? null;
        if (isset($id)) {
            $document = $this->engine->getIndex()->getDocument($id);
            array_walk_recursive($document, function (&$elem) {
                if (is_a($elem, \DateTime::class)) $elem = "@@@DateTime:" . $elem->getTimestamp();
            });
        }


        if (!isset($document['id'])) {
            $errors[] = "ERROR : Document must have an 'id'";
        }
        if (!isset($document['type'])) {
            $errors[] = "ERROR : Document must have a 'type'";
        }

        return view(
            '/view/default/search/edit',
            [
                'meta'  => Meta::get(__('search')),
                'user'  => $this->user,
                'data'  => [
                    'type'          => 'edit',
                    'sheet'         => 'edit',
                    'document'      => $document,
                    'errors'        => $errors,
                ]
            ]
        );
    }

    // Fetch document and redirect on success
    public function search()
    {
        $search_id = Request::getPostInt('search_id');
        if (isset($search_id)) {
            $document = $this->engine->getIndex()->getDocument($search_id);
            if ($document) {
                redirect(url('admin.search.edit.form', ['id' => $search_id]));
            }
            redirect(url('admin.search.query'));
        }
    }

    // deletes document
    public function remove()
    {
        $delete_id = Request::getPostInt('delete');
        if (isset($delete_id)) {
            $this->actions->delete($delete_id);
            $this->engine->getIndex()->getDocument($delete_id);
        }

        redirect(url('admin.search.query'));
    }

    // Edit document
    public function edit()
    {
        $content['id'] = Request::getPostInt('id');

        $_POST['content'] = empty($_POST['content'] ?? '') ? '{}' : $_POST['content'];
        $content = json_decode($_POST['content'], true);
        if ($content === null) {
            $errors[] = "ERROR : Invalid Json";
        } else {
            // we parse DateTimes back to their Object
            array_walk_recursive($content, function (&$elem) {
                if (strpos($elem, '@@@DateTime:') === 0) {
                    $ts = substr($elem, 12);
                    $elem = new \DateTime();
                    $elem->setTimestamp($ts);
                }
            });
            if (!isset($content['id'])) {
                $errors[] = "ERROR : Document must have an 'id'";
            }
            if (!isset($content['type'])) {
                $errors[] = "ERROR : Document must have a 'type'";
            }
        }
        if (empty($errors)) {
            // if everything's okay we create/update the document
            try {
                $this->actions->update($content);
            } catch (\Throwable $exception) {
                $errors[] = get_class($exception) . ": " . $exception->getMessage();
            }
        }
        if (isset($content['id'])) $_GET['id'] = $content['id'];

        redirect(url('admin.search.edit.form', ['id' => $content['id']]));
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
