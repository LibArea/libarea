<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Base\Controller;
use Hleb\Static\Request;
use App\Models\SubscriptionModel;

class SubscriptionController extends Controller
{
    public function index()
    {
        $type = Request::post('type')->value();
        $allowed = ['post', 'facet', 'category', 'item'];
        if (!in_array($type, $allowed)) return false;

        $content_id = Request::post('content_id')->asInt();
        if ($content_id <= 0) return false;

        SubscriptionModel::focus($content_id, $type);

        return true;
    }
}
