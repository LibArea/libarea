<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{FavoriteModel, PostModel, AnswerModel};
use Base, Translate;

class FavoriteController extends MainController
{
    public function index($type)
    {
        $content_id = Request::getPostInt('content_id');
        if ($type == 'post') {
            $content    = PostModel::getPostId($content_id);
        } else {
            $content    = AnswerModel::getAnswerId($content_id);
        }

        Base::PageRedirection($content, '/');

        $uid            = Base::getUid();
        $type_content   = $type == 'post' ? 1 : 2;
        $action = FavoriteModel::setFavorite($content_id, $uid['user_id'], $type_content);

        $lang = Translate::get('bookmark deleted');
        if ($action == 'add') {
            $lang = Translate::get('bookmark added');
        }

        return $lang;
    }
}
