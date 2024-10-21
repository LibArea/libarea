<?php

namespace App\Middlewares;

use Hleb\Base\Middleware;
use Hleb\Static\Router;
use App\Bootstrap\Services\Auth\RegType;

class DefaultMiddleware extends Middleware
{
    function index()
    {
        $data = Router::data();

        $type = $data[0] ?? RegType::USER_ZERO_LEVEL;
        $compare = $data[1] ?? '>=';

        $check = RegType::check($type, $compare);
        if (!$check) {
            redirect('/');
        }
    }
}
