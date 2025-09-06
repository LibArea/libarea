<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{FavoriteModel, CommentModel};

class FavoriteController extends Controller
{
    public function index()
    {
        $content_id = Request::post('content_id')->asInt();
        $type       = Request::post('type')->value();

        $allowed = ['post', 'comment'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        self::redirectItem($content_id, $type);

        FavoriteModel::setFavorite($content_id, $type);

        return __('app.successfully');
    }

    public static function redirectItem(int $content_id, string $type)
    {
        $content = '';
        switch ($type) {
            case 'post':
                $content  = FavoriteModel::checkingPost($content_id);
                break;
            case 'comment':
                $content  = CommentModel::getCommentId($content_id);
                break;
        }

        if (!$content) exit;

        return true;
    }
}
