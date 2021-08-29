<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Lori\{Content, Config, Base, Validation};

class SearchController extends MainController
{
    public function index()
    {
        $query  = '';
        $result = '';
        if (Request::getPost()) {

            $qa     =  Request::getPost('q');
            $query  = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $qa);

            if (!empty($query)) {

                Validation::Limits($query, lang('Too short'), '3', '128', '/search');

                // Успех и определим, что будем использовать
                // Далее индивидуально расширим (+ лайки, просмотры и т.д.)
                if (Config::get(Config::PARAM_SEARCH) == 0) {
                    $qa     =  SearchModel::getSearch($query);
                    $result = array();
                    foreach ($qa as $ind => $row) {
                        $row['post_content']  = Content::text(Base::cutWords($row['post_content'], 32, '...'), 'text');
                        $result[$ind]   = $row;
                    }
                    
                    $tags = [];
                    
                } else {
                    $result = SearchModel::getSearchPostServer($query);
                    $tags   = SearchModel::getSearchTagsServer($query);
                }
            } else {
                Base::addMsg(lang('Empty request'), 'error');
                redirect('/search');
            }
        }

        $meta = [
            'sheet'         => 'search',
            'meta_title'    => lang('Search'),
        ];

        $data = [
            'result'    => $result,
            'query'     => $query,
            'tags'      => $tags,
        ];

        return view('/search/index', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
