<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\SearchModel;
use Translate, UserData;

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

            $result = SearchModel::getSearch($page, $this->limit, $stem);

            $count  = SearchModel::getSearchCount($stem);
        }

        $quantity   = $count ?? null;

        $results = [];
        foreach ($result as $ind => $row) {
            $search = preg_quote($stem);
            $row['content'] = preg_replace("/($search)/ui", "<mark>$1</mark>", $row['content']);
            $row['title'] = preg_replace("/($search)/ui", "<mark>$1</mark>", $row['title']);
            $results[$ind]              = $row;
        }

        (new \Modules\Search\App\Search())->setLogs(
            [
                'request'       => $query,
                'action_type'   => 'website',
                'add_ip'        => Request::getRemoteAddress(),
                'user_id'       => $this->user['id'],
                'count_results' => $quantity ?? 0,
            ]
        );

        return view(
            '/view/default/search',
            [
                'meta'  => meta($m = [], Translate::get('search')),
                'user'  => $this->user,
                'data'  => [
                    'result'        => $results,
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
