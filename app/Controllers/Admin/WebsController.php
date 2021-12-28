<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\WebModel;
use Base, Content, Translate;

class WebsController extends MainController
{
    private $uid;

    protected $limit = 25;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = WebModel::getItemsAllCount();
        $domains    = WebModel::getItemsAll($page, $this->limit, $this->uid['user_id']);

        $result = [];
        foreach ($domains as $ind => $row) {
            $row['item_content']    = Content::text($row['item_content_url'], 'line');
            $result[$ind]           = $row;
        }

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return agRender(
            '/admin/web/webs',
            [
                'meta'  => meta($m = [], Translate::get('domains')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'domains'       => $result,
                ]
            ]
        );
    }

    public function favicon()
    {
        $item_id    = Request::getPostInt('id');
        $item       = WebModel::getItemId($item_id);
        pageError404($item);

        $puth = HLEB_PUBLIC_DIR . AG_PATH_FAVICONS . $item["item_url_domain"] . '.png';
        $dirF = HLEB_PUBLIC_DIR . AG_PATH_FAVICONS;

        if (!file_exists($puth)) {
            $urls = self::getFavicon($item['item_url_domain']);
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
