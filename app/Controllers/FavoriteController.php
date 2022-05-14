<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\{FavoriteModel, PostModel, AnswerModel};

class FavoriteController extends Controller
{
    public function index()
    {
        $content_id = Request::getPostInt('content_id');
        $type       = Request::getPost('type');

        $allowed = ['post', 'website', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        self::redirectItem($content_id, $type, $this->user);

        FavoriteModel::setFavorite(
            [
                'tid'           => $content_id,
                'user_id'       => $this->user['id'],
                'action_type'   => $type,
            ]
        );

        return __('app.successfully');
    }

    public static function redirectItem($content_id, $type, $user)
    {
        switch ($type) {
            case 'post':
                $content  = PostModel::getPost($content_id, 'id', $user);
                break;
            case 'website':
                $content  = (new \Modules\Catalog\App\Catalog())->getItemId($content_id);
                break;
            case 'answer':
                $content  = AnswerModel::getAnswerId($content_id);
                break;
        }

        if (!$content) exit;

        return true;
    }
}
