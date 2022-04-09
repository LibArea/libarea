<?php

namespace Modules\Search\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Search\App\Models\SearchModel;
use Translate, Config, UserData, Meta, Html, Content;

use Wamania\Snowball\StemmerFactory;
use voku\helper\StopWords;

class Search
{
    protected $limit = 100;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $query  = Request::getGet('q');

        $type   = Request::getGet('type');
        $type   = $type ?? 'post';

        $arr = ['post', 'website'];
        if (!in_array($type, $arr)) {
            redirect(getUrlByName('search'));
        }

        if ($query) {

            if ($query == '') {
                redirect(getUrlByName('search'));
            }

            $query  = self::stemmerAndStopWords($query);
            $results = self::search($page, $this->limit, $query, $type);
            $count  = self::searchCount($query, $type);

            self::setLogs(
                [
                    'request'       => $query,
                    'action_type'   => $type,
                    'add_ip'        => Request::getRemoteAddress(),
                    'user_id'       => $this->user['id'],
                    'count_results' => $count ?? 0,
                ]
            );
        }

        $result = [];
        if(!empty($results)) {
            foreach ($results as $ind => $row) {
                $text = Html::fragment($row['content']);
                $row['content'] = Content::text($text, 'line');
                $result[$ind]   = $row;
            }
        }

        $count = $count ?? 0;
        $facet = $type == 'post' ? 'topic' : 'category';
        return view(
            '/view/default/search',
            [
                'meta'  => Meta::get(Translate::get('search')),
                'user'  => $this->user,
                'data'  => [
                    'result'        => $result ?? false,
                    'type'          => $type,
                    'sheet'         => 'admin',
                    'query'         => $query ?? false,
                    'count'         => $count,
                    'pagesCount'    => ceil($count / $this->limit),
                    'pNum'          => $page,
                    'tags'          => self::searchTags($query, $facet, 4),
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

    public static function stemmerAndStopWords($query)
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        $lang = Translate::getLang();
        $lang = $lang == 'zh' ? 'en' : $lang;

        $stopWords      = new StopWords();
        $result         = $stopWords->getStopWordsAll();
        $stemmer        = StemmerFactory::create($lang);
        $arr_stop_words = $result[$lang];

        if (Config::get('general.lang') == 'ru') {
            $stemmer    = StemmerFactory::create('russian');
        } else {
            $stemmer    = StemmerFactory::create('english');
        }

        $qa = implode(" ", array_diff(explode(" ", $query), $arr_stop_words));

        return $stemmer->stem($qa);
    }


    public static function search($page, $limit, $query, $type)
    {
        return SearchModel::getSearch($page, $limit, $query, $type);
    }

    public static function searchCount($query, $type)
    {
        return SearchModel::getSearchCount($query, $type);
    }

    public static function searchTags($query, $type, $limit)
    {
        return SearchModel::getSearchTags($query,  $type, $limit);
    }

    public function api()
    {
        $topics = SearchModel::getSearchTags(Request::getPost('q'), 'topic', 5);
        $posts  = SearchModel::getSearch(1, 5, Request::getPost('q'), 'post');
        $result = array_merge($topics, $posts);

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
