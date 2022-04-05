<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\SubscriptionModel;
use UserData;

class SubscriptionController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index()
    {
        $content_id = Request::getPostInt('content_id');
        $type       = Request::getPost('type');

        $allowed = ['post', 'facet', 'category', 'item'];
        if (!in_array($type, $allowed)) return false;
        if ($content_id <= 0) return false;

        SubscriptionModel::focus($content_id, $this->user['id'], $type);

        return true;
    }
}
