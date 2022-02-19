<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\UserAreaModel;
use Content, Translate, UserData;

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

        $pagesCount = UserAreaModel::getUserSitesCount($this->user);
        $items  = UserAreaModel::getUserSites($page, $this->limit, $this->user);

        $result = [];
        foreach ($items as $ind => $row) {
            $text = explode("\n", $row['item_content_url']);
            $row['item_content_url']    = Content::text($text[0], 'line');
            $result[$ind]           = $row;
        }

        $num = $page > 1 ? sprintf(Translate::get('page-number'), $page) : '';

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/assets/images/agouti-web.png',
            'url'        => getUrlByName($sheet),
        ];

        return view(
            '/view/default/user/user-sites',
            [
                'meta'  => meta($m, Translate::get('my.site'), Translate::get('my.site')),
                'user'  => $this->user,
                'data'  => [
                    'screening'     => 'cat',
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'count'         => $pagesCount,
                    'pNum'          => $page,
                    'items'         => $result,
                    'type'          => $type,
                    'sheet'         => $sheet,
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

        return view(
            '/view/default/user/bookmarks',
            [
                'meta'  => meta([], Translate::get('favorites'), Translate::get('favorites')),
                'user' => $this->user,
                'data'  => [
                    'screening'     => 'cat',
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'count'         => $pagesCount,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'items'         => $items,
                ]
            ]
        );
    }
}
