<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\SubscriptionModel;

class SubscriptionController extends MainController
{
    public function index()
    {
        $account    = Request::getSession('account');
        $content_id = Request::getPostInt('content_id');
        $type       = Request::get('type');
        $user_id    = $account['user_id'];

        $allowed = ['post', 'topic'];
        if (!in_array($type, $allowed)) return false;

        if ($content_id <= 0) return false;

        SubscriptionModel::focus($content_id, $user_id, $type);

        return true;
    }
}
