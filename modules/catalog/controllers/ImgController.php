<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Catalog\Models\WebModel;
use Img;

class ImgController extends Module
{
    public function favicon()
    {
        if (!$this->container->user()->admin()) {
            return false;
        }

        $item_id    = Request::post('id')->asInt();
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

    public static function getFavicon(string $url)
    {
        $url = str_replace("https://", '', $url);
        // "https://www.google.com/s2/favicons?domain=" . $url;
        return "https://favicon.yandex.net/favicon/" . $url;
    }

    public function screenshot()
    {
        if (!$this->container->user()->admin()) {
            return false;
        }

        $item_id    = Request::post('id')->asInt();
        $item       = WebModel::getItemId($item_id);
        notEmptyOrView404($item);

        $puth = HLEB_PUBLIC_DIR . Img::PATH['thumbs'] . host($item['item_url']) . '.png';

        if (!file_exists($puth)) {
            $urls = self::getScreenshot($item['item_url']);
            copy($urls, $puth);
        }

        return true;
    }


    public static function getScreenshot(string $url)
    {
        return "https://api.screenshotone.com/take?image_width=880&url=" . $url . "&access_key=" . config('integration', 'sc_access_key');
    }
}
