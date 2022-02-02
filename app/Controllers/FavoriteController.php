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
        self::redirectItem($content_id, $type);

        switch ($type) {
            case 'post':
                $type = 1;
                break;
            case 'answer':
                $type = 2;
                break;
            case 'item':
                $type = 3;
                break;
        }
        
        $action = FavoriteModel::setFavorite(
            [
                'favorite_tid'      => $content_id,
                'favorite_user_id'  => $this->user['id'],
                'favorite_type'     => $type,
            ]
        );

        return Translate::get('successfully');
    }
    
    public static function redirectItem($content_id, $type)
    {
        switch ($type) {
            case 'post':
                $content    = PostModel::getPost($content_id, 'id', $this->user);
                break;
            case 'item':
                $content    = (new \Modules\Catalog\App\Catalog()) -> getItemId($content_id);
                break;
            case 'answer':
                $content    = AnswerModel::getAnswerId($content_id);
                break;
        }

        pageRedirection($content, '/');
    }
}
