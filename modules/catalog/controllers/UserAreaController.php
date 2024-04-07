<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Base\Module;
use Modules\Catalog\Models\UserAreaModel;
use Meta, Html;

class UserAreaController extends Module
{
    protected $limit = 15;

    public function index()
    {
        $pagesCount = UserAreaModel::getUserSitesCount();
        $items  = UserAreaModel::getUserSites(Html::pageNumber(), $this->limit);
        $count_site = $this->container->user()->admin() ? 0 : $pagesCount;

        return view(
            'user-sites',
            [
                'meta'  => Meta::get(__('web.my_website')),
                'user'  => $this->container->user()->get(),
                'data'  => [
                    'screening'         => 'all',
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'count'             => $pagesCount,
                    'pNum'              => Html::pageNumber(),
                    'items'             => $items,
                    'user_count_site'   => $count_site,
                    'sheet'             => 'sites',
                ]
            ],
        );
    }

    /**
     * Bookmarks by sites
     * Закладки по сайтам
     *
     * @return void
     */
    public function bookmarks()
    {
        $items      = UserAreaModel::bookmarks(Html::pageNumber(), $this->limit);
        $pagesCount = UserAreaModel::bookmarksCount();

        $count_site = $this->container->user()->admin() ? 0 : UserAreaModel::getUserSitesCount();

        return view(
            'bookmarks',
            [
                'meta'  => Meta::get(__('web.favorites')),
                'data'  => [
                    'screening'         => 'cat',
                    'sheet'             => 'web.bookmarks',
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'user_count_site'   => $count_site,
                    'pNum'              => Html::pageNumber(),
                    'items'             => $items,
                ]
            ],
        );
    }
}
