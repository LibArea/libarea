<?php

namespace App\Controllers\Api;

use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Config;

class SearchController
{
    public function index()
    {
        $type   = Config::get('general.search') == false ? 'mysql' : 'server';
        $topics = SearchModel::getSearchTags(Request::getPost('q'), $type, 5);

        if ($type == 'mysql') {
            $posts = SearchModel::getSearch(Request::getPost('q'), 5);
            $result = array_merge($topics, $posts);

            return json_encode($result, JSON_PRETTY_PRINT);
        }

        $posts = SearchModel::getSearchPostServer(Request::getPost('q'), 5);
        $result = array_merge($topics, $posts);

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
