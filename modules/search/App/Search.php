<?php

namespace Modules\Search\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Search\App\Models\SearchModel;
use Wamania\Snowball\StemmerFactory;
use UserData, Meta;

class Search
{
    protected $limit = 10;

    public function index()
    {
        $desc  = __('search.desc', ['name' => config('meta.name')]);
        return view(
            '/view/default/home',
            [
                'meta'  => Meta::get(__('search.title'), $desc),
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
        $type   = Request::getGet('cat');

        if (!in_array($type, ['post', 'website'])) {
            $type = 'post';
        }

        $sw = microtime(true);

        if ($q) {

            $lang = config('general.lang');
            if (!in_array($lang, ['ru', 'en', 'ro', 'fr', 'de'])) {
                $lang = 'en';
            }

            $stemmer = StemmerFactory::create($lang);
            $stem = $stemmer->stem($q);

            /* $words = explode(' ', $q);
            foreach($words as $key => $word) {
                if(strlen($word) >= 3) {
                    $words[$key] = '+' . $word . '*';
                }
            }
            $q = implode(' ', $words); */

            $results = self::search($pageNumber, $this->limit, $stem, $type);
            $count =  SearchModel::getSearchCount($stem, $type);

            $user_id = UserData::getUserId();
            self::setLogs(
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
        return view(
            '/view/default/search',
            [
                'meta'  => Meta::get(__('search.title')),
                'data'  => [
                    'results'       => $results ?? false,
                    'type'          => $type,
                    'sheet'         => 'admin',
                    'q'             => $q,
                    'tags'          => self::searchTags($q, $facet, 4),
                    'sw'            => (microtime(true) - $sw ?? 0) * 1000,
                    'count'         => $count,
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
