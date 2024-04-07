<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\IgnoredModel;

class IgnoredController extends Controller
{
    public function index()
    {
        $user_id = Request::post('user_id')->asInt();

        IgnoredModel::setIgnored($user_id);

        return __('app.successfully');
    }
}
