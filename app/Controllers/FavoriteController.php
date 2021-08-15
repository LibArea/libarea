<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\FavoriteModel;
use App\Models\PostModel;
use App\Models\AnswerModel;
use Lori\Base;

class FavoriteController extends \MainController
{
    public function index($type)
    {
        $uid        = Base::getUid();
        $content_id = \Request::getPostInt('content_id');

        if ($type == 'post') {
            $content    = PostModel::getPostId($content_id);
        } else {
            $content    = AnswerModel::getAnswerId($content_id);
        }

        Base::PageRedirection($content);

        $type_content = $type == 'post' ? 1 : 2;
        FavoriteModel::setFavorite($content_id, $uid['user_id'], $type_content);

        return true;
    }
}
