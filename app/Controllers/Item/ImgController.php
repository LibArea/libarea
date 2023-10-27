<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\Item\WebModel;
use UserData, Img;

class ImgController extends Controller
{
    public function favicon()
    {
        if (!UserData::checkAdmin()) {
            return false;
        }

        $item_id    = Request::getPostInt('id');
        $item       = WebModel::getItemId($item_id);
        notEmptyOrView404($item);

        $host = host($item['item_url']);
        $puth = HLEB_PUBLIC_DIR . Img::PATH['favicons'] . $host . '.png';

        if (!file_exists($puth)) {
            $urls = self::getFavicon($host);
            copy($urls, $puth);
        }

        return true;
    }

    public static function getFavicon($url)
    {
        $url = str_replace("https://", '', $url);
        // "https://www.google.com/s2/favicons?domain=" . $url;
        return "https://favicon.yandex.net/favicon/" . $url;
    }

    public function screenshot()
    {
        if (!UserData::checkAdmin()) {
            return false;
        }

        $item_id    = Request::getPostInt('id');
        $item       = WebModel::getItemId($item_id);
        notEmptyOrView404($item);

        $puth = HLEB_PUBLIC_DIR . Img::PATH['thumbs'] . host($item['item_url']) . '.png';

        if (!file_exists($puth)) {
            $urls = self::getScreenshot($item['item_url']);
            copy($urls, $puth);
        }

        return true;
    }


    public static function getScreenshot($url)
    {
        return "https://api.screenshotone.com/take?image_width=880&url=" . $url . "&access_key=" . config('integration.sc_access_key');
    }
}
