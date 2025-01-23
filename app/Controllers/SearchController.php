<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\SearchModel;
use Meta, Html;

class SearchController extends Controller
{
    protected $limit = 10;

    /**
     * Search engine home page
     * Главная страница поисковой системы
     */
    public function index(): View
    {
        $this->container->user()->id();

        return view(
            '/default/content/search/home',
            [
                'meta'  => Meta::get(__('search.title'), __('search.desc', ['name' => config('meta', 'name')])),
            ]
        );
    }

    public function openSearch()
    {
        insertCacheTemplate('/default/content/search/open-search', sec: 28800); // 8 часов
    }

    public function go()
    {
        $q      = Request::post('q')->value();
        $type   = Request::post('cat')->value();

        if (!in_array($type, ['post', 'website', 'comment'])) {
            $type = 'post';
        }

        $sw = microtime(true);

        if ($q) {

            $lang = config('general', 'lang');
            if (!in_array($lang, ['ru', 'en'])) {
                $lang = 'en';
            }

            $results = SearchModel::getSearch(Html::pageNumber(), $this->limit, $q, $type);

            $count_results =  SearchModel::getSearchCount($q, $type);

            $user_id = $this->container->user()->id();
            SearchModel::setSearchLogs(
                [
                    'request'       => $q,
                    'action_type'   => $type,
                    'add_ip'        => Request::getUri()->getIp(),
                    'user_id'       => $user_id > 0 ? $user_id : 1,
                    'count_results' => $count_results,
                ]
            );
        }

        $count = $count_results ?? 0;

        $facet = $type === 'post' ? 'topic' : 'category';
        render(
            '/search/search',
            [
                'meta'  => Meta::get(__('search.title')),
                'data'  => [
                    'results'       => $results ?? false,
                    'type'          => $type,
                    'sheet'         => 'admin',
                    'q'             => $q ?? null,
                    'tags'          => SearchModel::getSearchTags($q ?? null, $facet, 4),
                    'sw'            => round((microtime(true) - $sw ?? 0) * 1000, 4),
                    'count'         => $count,
                    'pagesCount'    => ceil($count / $this->limit),
                    'pNum'          => Html::pageNumber(),
                ]
            ],
            'search',
        );
    }

	public function searchPage() {
		
	}

    public function api()
    {
        $query = $this->validateInput(Request::post('query'));
        $type = $this->validateType(Request::post('type'));

        $content = $type === 'topic' ? 'post' : 'website';

        $topics = SearchModel::getSearchTags($query, $type, 3);
        $posts = SearchModel::getSearch(1, 5, $query, $content);

        $result = array_merge($topics, $posts);

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    private function validateInput($input)
    {
        return preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $input->asString());
    }

    private function validateType($type)
    {
        $belong = $type->asString();
        return $belong === 'topic' ? 'topic' : 'category';
    }

    // Related posts, content author change, facets 
    // Связанные посты, изменение автора контента, фасеты
    public function select()
    {
        $type       = $this->validateInput(Request::param('type'));
        $search     = Request::post('q')->value();

        return SearchModel::getSelect($search, $type);
    }
}
