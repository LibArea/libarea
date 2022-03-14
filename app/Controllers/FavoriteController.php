<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{FavoriteModel, PostModel, AnswerModel};
use Translate, UserData;

class FavoriteController extends MainController
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

        $allowed = ['post', 'website', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }
        
        self::redirectItem($content_id, $type, $this->user);

        $action = FavoriteModel::setFavorite(
            [
                'tid'           => $content_id,
                'user_id'       => $this->user['id'],
                'action_type'   => $type,
            ]
        );

        return Translate::get('successfully');
    }

    public static function redirectItem($content_id, $type, $uid)
    {
        switch ($type) {
            case 'post':
                $content  = PostModel::getPost($content_id, 'id', $uid);
                break;
            case 'website':
                $content  = (new \Modules\Catalog\App\Catalog())->getItemId($content_id);
                break;
            case 'answer':
                $content  = AnswerModel::getAnswerId($content_id);
                break;
        }

        pageRedirection($content, '/');
    }

}
