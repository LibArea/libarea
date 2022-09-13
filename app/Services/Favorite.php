<?php

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Models\Item\WebModel;
use App\Models\{FavoriteModel, PostModel, AnswerModel};

class Favorite extends Base
{
    public function index()
    {
        $content_id = Request::getPostInt('content_id');
        $type       = Request::getPost('type');

        $allowed = ['post', 'website', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        self::redirectItem($content_id, $type);

        FavoriteModel::setFavorite($content_id, $type);

        return __('app.successfully');
    }

    public static function redirectItem($content_id, $type)
    {
        switch ($type) {
            case 'post':
                $content  = PostModel::getPostId($content_id);
                break;
            case 'website':
                $content  = WebModel::getItemId($content_id);
                break;
            case 'answer':
                $content  = AnswerModel::getAnswerId($content_id);
                break;
        }

        if (!$content) exit;

        return true;
    }
}
