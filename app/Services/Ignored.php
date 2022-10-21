<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Models\IgnoredModel;

class Ignored extends Base
{
    public function index()
    {
        $user_id = Request::getPostInt('user_id');

        IgnoredModel::setIgnored($user_id);

        return __('app.successfully');
    }

}
