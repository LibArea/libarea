<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use Modules\Catalog\App\Models\{WebModel, SearchModel};
use Content, Translate;

class Search
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $query  = Request::getPost('q');

        if (Request::getPost()) {
            if ($query == '') {
                redirect(getUrlByName('search'));
            }

            $stem   = (new \Modules\Search\App\Search())->stemmer($query);

            $result = SearchModel::getSearch($page, $this->limit, $query);

            $count  = SearchModel::getSearchCount($query);
        }

        $result     = $result ?? null;
        $quantity   = $count ?? null;

        return view(
            '/view/default/search',
            [
                'meta'  => meta($m = [], Translate::get('search')),
                'user'  => $this->user,
                'data'  => [
                    'result'        => $result ?? null,
                    'type'          => 'admin',
                    'sheet'         => 'admin',
                    'query'         => $query ?? null,
                    'count'         => $quantity,
                    'pagesCount'    => ceil($quantity / $this->limit),
                    'pNum'          => $page,
                    'tags'          => SearchModel::getSearchTags($query, 10),
                ]
            ]
        );
    }
}
