<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{FavoriteModel, PostModel, AnswerModel};
use Translate;

class FavoriteController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index($type)
    {
        $content_id = Request::getPostInt('content_id');
        if ($type == 'post') {
            $content    = PostModel::getPost($content_id, 'id', $this->user);
        } else {
            $content    = AnswerModel::getAnswerId($content_id);
        }

        pageRedirection($content, '/');

        $action = FavoriteModel::setFavorite(
            [
                'favorite_tid'      => $content_id,
                'favorite_user_id'  => $this->user['id'],
                'favorite_type'     => ($type == 'post') ? 1 : 2,
            ]
        );

        $lang = Translate::get('bookmark deleted');
        if ($action == 'add') $lang = Translate::get('bookmark added');

        return $lang;
    }
}
