<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{SubscriptionModel, PostModel, FacetModel};

class SubscriptionController extends MainController
{
    public function index()
    {
        $account    = Request::getSession('account');
        $content_id = Request::getPostInt('content_id');
        $type       = Request::get('type');

        $allowed = ['post', 'topic'];
        if (!in_array($type, $allowed)) return false;

        if ($content_id <= 0) return false;

        if ($type == 'post') {
            $content = PostModel::getPostId($content_id);
        } else {
            $content =  FacetModel::getFacet($content_id, 'id');
            // Запретим владельцу отписываться от созданного фасета
            if ($content['facet_user_id'] == $account['user_id']) return false;
        }

        SubscriptionModel::focus($content_id, $account['user_id'], $type);

        return true;
    }
}
