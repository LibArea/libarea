<?php

namespace Modules\Catalog\App;

use Modules\Catalog\App\Models\{WebModel, UserAreaModel};
use Domain, UserData, Validation, Config;

class Сhecks
{
    public const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0';
    
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public static function permissions($user, $allowed_tl)
    {
        if ($user['trust_level'] < $allowed_tl) {
            return false;
        }
        return true;
    }

    public static function length($content, $min, $max)
    {
        if (getStrlen($content) < $min || getStrlen($content) > $max) {
            return false;
        }

        return true;
    }

    public static function getDomain($url)
    {
        $basic_host = self::domain($url);

        return WebModel::getItemOne($basic_host, 1);
    }

    public static function domain($url)
    {
        $parse      = parse_url($url);
        $domain     = new Domain($parse['host']);

        return $domain->getRegisterable();
    }

    public function limit()
    {
        $count      = UserAreaModel::getUserSitesCount($this->user['id']);

        $count_add  = UserData::checkAdmin() ? 999 : Config::get('trust-levels.count_add_site');

        $in_total   = $count_add - $count;

        Validation::validTl($this->user['trust_level'], Config::get('trust-levels.tl_add_site'), $count, $count_add);

        if (!$in_total > 0) {
            redirect(getUrlByName('web'));
        }

        return $in_total;
    }
    
    public static function checkStatus(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $page = curl_exec($ch);

        $err = curl_error($ch);
        if (!empty($err))  
        return $err;

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpcode;
    }
}
