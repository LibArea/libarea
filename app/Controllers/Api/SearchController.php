<?php

namespace App\Controllers\Api;

use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Config;

class SearchController
{
    public function index()
    {
        if (Config::get('general.search') == 0) { 
            return json_encode (SearchModel::getSearch(Request::getPost('q'), 5), JSON_PRETTY_PRINT);
        } 

        return json_encode (SearchModel::getSearchPostServer(Request::getPost('q'), 5), JSON_PRETTY_PRINT); 
    }
 
 
} 