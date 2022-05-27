<?php

namespace App\Controllers\Item;

use App\Models\Item\UserAreaModel;
use App\Controllers\Controller;
use UserData, Meta;

class UserAreaController extends Controller
{
    protected $limit = 15;

    public function index()
    {
        $pagesCount = UserAreaModel::getUserSitesCount($this->user['id']);
        $items  = UserAreaModel::getUserSites($this->pageNumber, $this->limit, $this->user['id']);
        $count_site = UserData::checkAdmin() ? 0 : $pagesCount;

        return $this->render(
            '/item/user-sites',
            'item',
            [
                'meta'  => Meta::get(__('web.my_website')),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'all',
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'count'             => $pagesCount,
                    'pNum'              => $this->pageNumber,
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
        $items      = UserAreaModel::bookmarks($this->pageNumber, $this->limit, $this->user['id']);
        $pagesCount = UserAreaModel::bookmarksCount($this->user['id']);

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return $this->render(
            '/item/bookmarks',
            'item',
            [
                'meta'  => Meta::get(__('web.favorites')),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'cat',
                    'sheet'             => 'web.bookmarks',
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'user_count_site'   => $count_site,
                    'pNum'              => $this->pageNumber,
                    'items'             => $items,
                ]
            ]
        );
    }
}
