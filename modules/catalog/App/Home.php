<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use Content, Translate, UserData;

class Home
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = WebModel::getItemsAllCount($sheet);
        $items      = WebModel::getItemsAll($page, $this->limit, $this->user, $sheet);

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

        switch ($sheet) {
            case 'web':
                $title  = Translate::get('site.directory');
                $desc   = Translate::get('web.desc');
                break;
            case 'web.all':
                $title  = Translate::get('web.all');
                $desc   = Translate::get('web.all.desc');
                break;
            case 'web.top':
                $title  = Translate::get('web.top.title');
                $desc   = Translate::get('web.top.desc');
                break;
        }

        return view(
            '/view/default/home',
            [
                'meta'  => meta($m, $title, $desc),
                'user'  => $this->user,
                'data'  => [
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
}
