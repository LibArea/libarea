<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;

class Map
{
    private $user;

    protected $limit = 25;

    const MAX_LEVEL = 10;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index()
    {
        $map = [];
        for ($i = 1; $i < self::MAX_LEVEL + 1; $i++) {
           if (\Request::get("name-$i") && \Request::get("value-$i") !== null) {
               $map[\Request::get("name-$i")] = \Request::get("value-$i");
           } else {
               break;
           }
        }

        return $this->action($map);
    }

    protected function action(array $map) {
        var_dump($map);
    }

}
