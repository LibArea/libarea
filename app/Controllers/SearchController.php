<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use UserData, Meta, LinguaStem;

class SearchController extends Controller
{
    protected $limit = 10;

    public function index()
    {
        return view(
            '/default/content/search/home',
            [
                'meta'  => Meta::get(__('search.title'), __('search.desc', ['name' => config('meta.name')])),
            ]
        );
    }

    public function go()
    {
        $pageNumber = self::number(Request::getGetInt('page'));

        $q      = Request::getGet('q');
        $type   = Request::getGet('cat');

        if (!in_array($type, ['post', 'website', 'answer'])) {
            $type = 'post';
        }

        $sw = microtime(true);

        if ($q) {

            $lang = config('general.lang');
            if (!in_array($lang, ['ru', 'en'])) {
                $lang = 'en';
            }
            $stem = new LinguaStem($lang);
            $stem = $stem->text($q);

            $results = SearchModel::getSearch($pageNumber, $this->limit, $q, $type);
            $count =  SearchModel::getSearchCount($q, $type);

            $user_id = UserData::getUserId();
            SearchModel::setSearchLogs(
                [
                    'request'       => $q,
                    'action_type'   => $type,
                    'add_ip'        => Request::getRemoteAddress(),
                    'user_id'       => $user_id > 0 ? $user_id : 1,
                    'count_results' => $count ?? 0,
                ]
            );
        }

        $facet = $type == 'post' ? 'topic' : 'category';
        return $this->render(
            '/search/search',
            [
                'meta'  => Meta::get(__('search.title')),
                'data'  => [
                    'results'       => $results ?? false,
                    'type'          => $type,
                    'sheet'         => 'admin',
                    'q'             => $q,
                    'tags'          => SearchModel::getSearchTags($stem, $facet, 4),
                    'sw'            => round((microtime(true) - $sw ?? 0) * 1000, 4),
                    'count'         => $count,
                    'pagesCount'    => ceil($count / $this->limit),
                    'pNum'          => $pageNumber,
                ]
            ], 'search',
        );
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

    public static function number($num)
    {
        return $num <= 1 ? 1 : $num;
    }
}
