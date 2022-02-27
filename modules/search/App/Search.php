<?php

namespace Modules\Search\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Search\App\Models\SearchModel;
use Modules\Search\App\Helper;
use Translate, Config, UserData;

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
        $query  = Request::getPost('q');
        $type   = Config::get('general.search') == false ? 'mysql' : 'server';

        if (Request::getPost()) {
            if ($query == '') {
                redirect(getUrlByName('search'));
            }

            $stem   = self::stemmerAndStopWords($query);

            $result = self::search($page, $this->limit, $stem, $type);

            $count  = SearchModel::getSearchCount($stem);
        }

        $result     = $result ?? null;
        $quantity   = $count ?? null;

        self::setLogs(
            [
                'request'       => $query,
                'action_type'   => 'post',
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
                    'result'        => $result ? Helper::handler($result) : null,
                    'type'          => 'admin',
                    'sheet'         => 'admin',
                    'query'         => $query ?? null,
                    'count'         => $quantity,
                    'pagesCount'    => ceil($quantity / $this->limit),
                    'pNum'          => $page,
                    'tags'          => SearchModel::getSearchTags($query, $type, 10),
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


    public static function search($page, $limit, $stem, $type)
    {
        if ($type == 'mysql') {
            return SearchModel::getSearch($page, $limit, $stem);
        }

        return SearchModel::getSearchPostServer($stem, 50);
    }

    public function api()
    {

        $type   = Config::get('general.search') == false ? 'mysql' : 'server';
        $topics = SearchModel::getSearchTags(Request::getPost('q'), $type, 5);

        if ($type == 'mysql') {
            $posts = SearchModel::getSearch(1, 5, Request::getPost('q'));
            $result = array_merge($topics, $posts);

            return json_encode($result, JSON_PRETTY_PRINT);
        }

        $posts = SearchModel::getSearchPostServer(Request::getPost('q'), 5);
        $result = array_merge($topics, $posts);

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
