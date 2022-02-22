<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, UserAreaModel};
use Content, Translate, UserData;

class Home
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

        $limit = 10;
        $pagesCount = 1;
        if ($sheet == 'web.deleted' || $sheet == 'web.audit') {
            $limit = $this->limit;
            $pagesCount = WebModel::getItemsAllCount($sheet);
        }

        $items  = WebModel::getItemsAll($page, $limit, $this->user, $sheet);

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

        $count_site = ($this->user['trust_level'] == UserData::REGISTERED_ADMIN) ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return view(
            '/view/default/home',
            [
                'meta'  => meta($m, Translate::get($sheet . '.home.title'), Translate::get($sheet . '.home.desc')),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'cat',
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'count'             => $pagesCount,
                    'pNum'              => $page,
                    'items'             => $result,
                    'user_count_site'   => $count_site,
                    'type'              => $type,
                    'sheet'             => $sheet,
                ]
            ]
        );
    }
}
