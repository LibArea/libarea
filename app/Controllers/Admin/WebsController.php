<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\WebModel;
use Base, Content, Translate;

class WebsController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit      = 25;
        $pagesCount = WebModel::getLinksAllCount();
        $domains    = WebModel::getLinksAll($page, $limit, $this->uid['user_id']);

        $result = array();
        foreach ($domains as $ind => $row) {
            $row['link_content']    = Content::text($row['link_content'], 'line');
            $result[$ind]           = $row;
        }

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return view(
            '/admin/web/webs',
            [
                'meta'  => meta($m = [], Translate::get('domains')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet == 'all' ? 'domains' : $sheet,
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'domains'       => $result,
                ]
            ]
        );
    }

    public function favicon()
    {
        $link_id    = Request::getPostInt('id');
        $link       = WebModel::getLinkId($link_id);
        Base::PageError404($link);

        $puth = HLEB_PUBLIC_DIR . AG_PATH_FAVICONS . $link["link_id"] . '.png';
        $dirF = HLEB_PUBLIC_DIR . AG_PATH_FAVICONS;

        if (!file_exists($puth)) {
            $urls = self::getFavicon($link['link_url_domain']);
            copy($urls, $puth);
        }

        return true;
    }

    public static function getFavicon($url)
    {
        $url = str_replace("https://", '', $url);
        return "https://www.google.com/s2/favicons?domain=" . $url;
    }
}
