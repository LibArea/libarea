<?php

namespace Modules\Team\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Team\App\Models\SearchModel;
use UserData;

class Search
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Request, search for members for team
    // Запрос, поиска участников для команд
    public function select()
    {
        $search     = Request::getPost('q');
        if ($search) {
            $search = preg_replace('/[^a-zA-Z0-9]/ui', '', $search);
        }

        return SearchModel::get($search, $this->user['id']);
    }
}
