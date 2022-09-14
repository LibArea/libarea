<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Models\SubscriptionModel;

class Subscription extends Base
{
    public function index()
    {
        $type = Request::getPost('type');
        $allowed = ['post', 'facet', 'category', 'item'];
        if (!in_array($type, $allowed)) return false;

        $content_id = Request::getPostInt('content_id');
        if ($content_id <= 0) return false;

        SubscriptionModel::focus($content_id, $type);

        return true;
    }
}
