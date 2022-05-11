<?php

namespace Modules\Catalog\App;

use Modules\Catalog\App\Models\UserAreaModel;
use UserData, Meta, Tpl;

class UserArea
{
    private $user;

    protected $limit = 15;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index()
    {
        $pageNumber = Tpl::pageNumber();

        $pagesCount = UserAreaModel::getUserSitesCount($this->user['id']);
        $items  = UserAreaModel::getUserSites($pageNumber, $this->limit, $this->user['id']);
        $count_site = UserData::checkAdmin() ? 0 : $pagesCount;

        $m = [
            'og'         => true,
            'imgurl'     => config('meta.img_path_web'),
        ];

        return view(
            '/view/default/user/user-sites',
            [
                'meta'  => Meta::get(__('web.my_website'), __('web.my_website'), $m),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'all',
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'count'             => $pagesCount,
                    'pNum'              => $pageNumber,
                    'items'             => $items,
                    'user_count_site'   => $count_site,
                    'sheet'             => 'sites',
                ]
            ]
        );
    }

    // Bookmarks by sites
    // Закладки по сайтам
    public function bookmarks()
    {
        $pageNumber = Tpl::pageNumber();

        $items      = UserAreaModel::bookmarks($pageNumber, $this->limit, $this->user['id']);
        $pagesCount = UserAreaModel::bookmarksCount($this->user['id']);

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return view(
            '/view/default/user/bookmarks',
            [
                'meta'  => Meta::get(__('web.favorites'), __('web.favorites')),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'cat',
                    'sheet'             => 'web.bookmarks',
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'user_count_site'   => $count_site,
                    'pNum'              => $pageNumber,
                    'items'             => $items,
                ]
            ]
        );
    }
}
