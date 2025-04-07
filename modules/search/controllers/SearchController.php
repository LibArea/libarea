<?php

declare(strict_types=1);

namespace Modules\Search\Controllers;

use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Search\Models\SearchModel;
use Meta, Html;

use S2\Rose\Stemmer\PorterStemmerEnglish;
use S2\Rose\Stemmer\PorterStemmerRussian;
use S2\Rose\Finder;
use S2\Rose\Entity\Query;

class SearchController extends Module
{
    protected $limit = 15;

    /**
     * Search engine home page
     * Главная страница поисковой системы
     */
    public function index(): View
    {
        $this->container->user()->id();

        return view(
            'home',
            [
                'meta'  => Meta::get(__('search.title'), __('search.desc', ['name' => config('meta', 'name')])),
            ]
        );
    }

    public function openSearch()
    {
        insertCacheTemplate('open-search', sec: 28800); // 8 часов
    }

    public function go()
    {
        $time_start = microtime(true);

        $type    = Request::get('cat')->value();
        $q    = Request::get('q')->value();


        if (config('general', 'search_engine') == true) {
            $storage = SearchModel::PdoStorage();

            $stemmer = new PorterStemmerRussian(new PorterStemmerEnglish());
            $finder    = new Finder($storage, $stemmer);

            $query = new Query($q);
            $query
                ->setLimit($this->limit)  // 10 results per page
                ->setOffset(Html::pageNumber() - 1) // third page
            ;

            $finder->setHighlightTemplate('<mark>%s</mark>'); // Выделим найденный фрагмент желтым
            $finder->setSnippetLineSeparator(' ... '); // Разделитель фрагментов, в данном случае

            $resultSet = $finder->find($query->setInstanceId(1));

            $totalHits = $resultSet->getTotalCount();

            $items = $resultSet->getItems();

            $result = [];
            foreach ($items as $key => $item) {
                $result[$key]['id'] = $item->getId();
                $result[$key]['url'] = $item->getUrl();
                $result[$key]['title'] = $item->getHighlightedTitle($stemmer);
                $result[$key]['content'] = $item->getSnippet();
            }
        }

        if (config('general', 'search_engine') == false) {

            $result = SearchModel::getSearch(Html::pageNumber(), $this->limit, $q);

            $totalHits =  SearchModel::getSearchCount($q);

            $resultFacetAll = SearchModel::getSearchTags($q ?? null, 'topic', 4);
        }

        $time_end = microtime(true);

        $user_id = $this->container->user()->id();
        SearchModel::setSearchLogs(
            [
                'request'       => $q,
                'action_type'   => 'search',
                'add_ip'        => Request::getUri()->getIp(),
                'user_id'       => $user_id > 0 ? $user_id : 1,
                'count_results' => $totalHits,
            ]
        );


        if (config('general', 'search_engine') == true) {
            // Поиск по фасетам, + свои правила выделения.
            $query
                ->setLimit(4)  // 4 results per page
                ->setOffset(0) // third page
            ;

            $finder->setHighlightTemplate('<b>%s</b>');
            $resultFacet = $finder->find($query->setInstanceId(2));
            $facets = $resultFacet->getItems();

            $resultFacetAll = [];
            foreach ($facets as $key => $item) {
                $resultFacetAll[$key]['slug'] = $item->getUrl();
                $resultFacetAll[$key]['title'] = $item->getHighlightedTitle($stemmer);
            }
        }

        return view(
            'search',
            [
                'meta'  => Meta::get(__('search.title')),
                'data'  => [
                    'results'       => $result,
                    'type'          => $type,
                    'sheet'         => 'admin',
                    'q'             => $q ?? null,
                    'tags'          => $resultFacetAll,
                    'time'            => $time_end - $time_start,
                    'count'         => $totalHits,
                    'pagesCount'    => ceil($totalHits / $this->limit),
                    'pNum'          => Html::pageNumber(),
                ]
            ]
        );
    }

    public function searchPage() {}
}
