<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{FavoriteModel, PostModel, AnswerModel};
use Translate;

class FavoriteController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = UserData::getUid();
    }

    public function index($type)
    {
        $content_id = Request::getPostInt('content_id');
        if ($type == 'post') {
            $content    = PostModel::getPost($content_id, 'id', $this->uid);
        } else {
            $content    = AnswerModel::getAnswerId($content_id);
        }

        pageRedirection($content, '/');

        $type_content   = $type == 'post' ? 1 : 2;
        $action = FavoriteModel::setFavorite($content_id, $this->uid['user_id'], $type_content);

        $lang = Translate::get('bookmark deleted');
        if ($action == 'add') $lang = Translate::get('bookmark added');

        return $lang;
    }
}
