<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{SubscriptionModel, PostModel, FacetModel};
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

        $allowed = ['post', 'facet', 'blog', 'category', 'item'];
        if (!in_array($type, $allowed)) return false;
        if ($content_id <= 0) return false;

        if ($type == 'post') {
            $content = PostModel::getPost($content_id, 'id', $this->user);
        } else {
            $content =  FacetModel::getFacet($content_id, 'id', $type);
            // Запретим владельцу отписываться от созданного фасета
            // Prevent the owner from unsubscribing from the created facet 
            if ($content['facet_user_id'] == $this->user['id']) return false;
        }

        SubscriptionModel::focus($content_id, $this->user['id'], $type);

        return true;
    }
}
