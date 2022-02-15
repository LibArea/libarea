<?php

namespace Modules\Catalog\App;

use Modules\Catalog\App\Models\WebModel;
use Translate, Domain;

class Сhecks
{
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
        $host       = $parse['host'];
        $domain     = new Domain($host);

        return $domain->getRegisterable();
    }

}
