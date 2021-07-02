<?php

/*
 *  Mapping to autoload classes: namespace => realpath
 *
 *  Сопоставление для автозагрузки классов: namespace => realpath
 */

namespace App\Optional;

use Hleb\Scheme\Home\Main\Connector;

class MainConnector implements Connector
{
    public function add() {
        return [
            "App\Controllers\*"             => "app/Controllers/",
            "Models\*"                      => "app/Models/",
            "App\Middleware\Before\*"       => "app/Middleware/Before/",
            "App\Middleware\After\*"        => "app/Middleware/After/",
            "Modules\*"                     => "modules/",
            "App\Commands\*"                => "app/Commands/",

            // ... or, if a specific class is added,
            "DB"                            => "database/DB.php",
            "XdORM\XD"                      => "vendor/phphleb/xdorm/XD.php",
            // "Phphleb\Debugpan\DPanel"    => "vendor/phphleb/debugpan/DPanel.php",

            "Lori\Base"                     => "app/Libraries/Base.php",
            "Lori\Content"                  => "app/Libraries/Content.php",
            "Lori\UploadImage"              => "app/Libraries/UploadImage.php",
            "Lori\Config"                   => "app/Config/Config.php",

            "UrlRecord"                     => "app/ThirdParty/UrlRecord/UrlRecord.php",
            
            "Parsedown"                     => "app/ThirdParty/Parsedown/Parsedown.php",
            "MyParsedown"                   => "app/ThirdParty/Parsedown/MyParsedown.php",
             
            "SimpleImage"                   => "app/ThirdParty/SimpleImage.php",
            "URLScraper"                    => "app/ThirdParty/URLScraper.php",
            // https://github.com/JacksonJeans/php-mail
            "JacksonJeans\MailException"    => "app/ThirdParty/php-mail/src/JacksonJeans/MailException.class.php",
            "JacksonJeans\Mail"             => "app/ThirdParty/php-mail/src/JacksonJeans/Mail.class.php",
        ];

    }
}

