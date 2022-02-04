<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use Modules\Catalog\App\Models\WebModel;
use Content, Translate;

class Webs
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
        $domains    = WebModel::getItemsAll($page, $this->limit, $this->user['id'], $sheet);

        $result = [];
        foreach ($domains as $ind => $row) {
            $row['item_content']    = Content::text($row['item_content_url'], 'line');
            $result[$ind]           = $row;
        }

        return view(
            '/view/default/web/webs',
            [
                'meta'  => meta($m = [], Translate::get('domains')),
                'user'  => $this->user,
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
        //return "https://www.google.com/s2/favicons?domain=" . $url;
        return "https://favicon.yandex.net/favicon/" . $url;
    }
}
