<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\UserAreaModel;
use Content, Config, Translate, UserData, Meta;

class UserArea
{
    private $user;

    protected $limit = 15;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = UserAreaModel::getUserSitesCount($this->user['id']);
        $items  = UserAreaModel::getUserSites($page, $this->limit, $this->user['id']);

        $num = $page > 1 ? sprintf(Translate::get('page-number'), $page) : '';

        $m = [
            'og'         => true,
            'imgurl'     => Config::get('meta.img_path_web'),
            'url'        => getUrlByName($sheet),
        ];

        return view(
            '/view/default/user/user-sites',
            [
                'meta'  => Meta::get($m, Translate::get('my.site'), Translate::get('my.site')),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'cat',
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'count'             => $pagesCount,
                    'pNum'              => $page,
                    'items'             => $items,
                    'user_count_site'   => $pagesCount,
                    'type'              => $type,
                    'sheet'             => $sheet,
                ]
            ]
        );
    }

    // Bookmarks by sites
    // Закладки по сайтам
    public function bookmarks($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $items      = UserAreaModel::bookmarks($page, $this->limit, $this->user['id']);
        $pagesCount = UserAreaModel::bookmarksCount($this->user['id']);

        $count_site = ($this->user['trust_level'] == UserData::REGISTERED_ADMIN) ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return view(
            '/view/default/user/bookmarks',
            [
                'meta'  => Meta::get([], Translate::get('favorites'), Translate::get('favorites')),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'cat',
                    'sheet'             => $sheet,
                    'type'              => $type,
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'user_count_site'   => $count_site,
                    'pNum'              => $page,
                    'items'             => $items,
                ]
            ]
        );
    }
}
