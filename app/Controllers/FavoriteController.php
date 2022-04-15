<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{FavoriteModel, PostModel, AnswerModel};
use Translate, UserData, Html;

class FavoriteController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index()
    {
        $arr    = Request::getJsonBodyList();

        $allowed = ['post', 'website', 'answer'];
        if (!in_array($arr['type'], $allowed)) {
            return false;
        }

        if (!filter_var($id = $arr['content_id'], FILTER_VALIDATE_INT)) {
            return false;
        }

        self::redirectItem($id, $arr['type'], $this->user);

        FavoriteModel::setFavorite(
            [
                'tid'           => $id,
                'user_id'       => $this->user['id'],
                'action_type'   => $arr['type'],
            ]
        );

        return Translate::get('successfully');
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
