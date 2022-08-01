<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\Item\WebModel;

class Webs extends Controller
{
    public function favicon()
    {
        $item_id    = Request::getPostInt('id');
        $item       = WebModel::getItemId($item_id);
        self::error404($item);

        $puth = HLEB_PUBLIC_DIR . PATH_FAVICONS . $item["item_domain"] . '.png';

        if (!file_exists($puth)) {
            $urls = self::getFavicon($item['item_domain']);
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
    
    public function screenshot()
    {
        $item_id    = Request::getPostInt('id');
        $item       = WebModel::getItemId($item_id);
        self::error404($item);

        $puth = HLEB_PUBLIC_DIR . PATH_THUMBS . $item["item_domain"] . '.png';

        if (!file_exists($puth)) {
            $urls = self::getScreenshot($item['item_url']);
            copy($urls, $puth);
        }

        return true;
    }
    
    
    public static function getScreenshot($url)
    {  
        return "https://api.screenshotone.com/take?image_width=880&url=" . $url ."&access_key=" . config('integration.sc_access_key');
    }
}
