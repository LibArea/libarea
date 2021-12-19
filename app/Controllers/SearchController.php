<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Base, Translate, Validation, Config, Content;

class SearchController extends MainController
{
    public function index()
    {
        $query  = $result = $tags   = '';
        $qa     = Request::getPost('q');
        $query  = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $qa);
        $type   = Config::get('general.search') == 0 ? 'mysql' : 'server';

        if (Request::getPost()) {
            if ($query == '') {
                addMsg(Translate::get('empty request'), 'error');
                redirect(getUrlByName('search'));
            }

            Validation::Limits($query, Translate::get('too short'), '3', '128', '/search');

            $qa     = self::searchPosts($query, $type);
            $count  = count($qa);
            $result = [];
            foreach ($qa as $ind => $row) {

                if ($type == 'mysql') {
                    $row['content'] = Content::text(cutWords($row['content'], 32, '...'), 'text');
                } else {
                    $row['content'] = Content::noMarkdown($row['content']);
                }

                $result[$ind]   = $row;
            }
        }
        return agRender(
            '/search/index',
            [
                'meta'  => meta($m = [], Translate::get('search')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'result'    => $result,
                    'query'     => $query,
                    'count'     => $count ?? 0,
                    'tags'      => SearchModel::getSearchTags($query, $type, 10),
                ]
            ]
        );
    }

    public static function searchPosts($query, $type)
    {
        if ($type == 'mysql') {
            return SearchModel::getSearch($query, 50);
        }

        return SearchModel::getSearchPostServer($query, 50);
    }
}
