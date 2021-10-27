<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Content, Config, Base, Validation, Translate;

class SearchController extends MainController
{
    public function index()
    {
        $query  = '';
        $result = '';
        $tags   = '';
        if (Request::getPost()) {

            $qa     =  Request::getPost('q');
            $query  = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $qa);

            if (!empty($query)) {

                Validation::Limits($query, Translate::get('too short'), '3', '128', '/search');

                if (Config::get('general.search') == 0) {
                    $qa     =  SearchModel::getSearch($query);
                    $result = [];
                    foreach ($qa as $ind => $row) {
                        $row['post_content']  = Content::text(cutWords($row['post_content'], 32, '...'), 'text');
                        $result[$ind]   = $row;
                    }
                    $count  = count($qa); 
                    $tags   = SearchModel::getSearchTags($query, 'mysql');
                } else {
                    $qa     = SearchModel::getSearchPostServer($query);
                    $tags   = SearchModel::getSearchTags($query, 'server');
                    $count  = count($qa); 
                    $result = [];
                    foreach ($qa as $ind => $row) {
                        $row['_content'] = Content::noMarkdown($row['_content']);
                        $result[$ind]    = $row;
                    }
                }
            } else {
                addMsg(Translate::get('empty request'), 'error');
                redirect(getUrlByName('search'));
            }
        }

        $meta = meta($m = [], Translate::get('search'));
        $data = [
            'result'    => $result,
            'query'     => $query,
            'count'     => $count ?? 0,
            'tags'      => $tags,
        ];

        return view('/search/index', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
