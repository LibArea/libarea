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
            // "Phphleb\Debugpan\DPanel"    => "app/ThirdParty/phphleb/debugpan/DPanel.php",

            "Agouti\Base"                     => "app/Libraries/Base.php",
            "Agouti\Content"                  => "app/Libraries/Content.php",
            "Agouti\UploadImage"              => "app/Libraries/UploadImage.php",
            "Agouti\Integration"              => "app/Libraries/Integration.php",
            "Agouti\Validation"               => "app/Libraries/Validation.php",
            "Agouti\Config"                   => "app/Config/Config.php",

            "UrlRecord"                     => "app/ThirdParty/UrlRecord/UrlRecord.php",
            "Parsedown"                     => "app/ThirdParty/Parsedown/Parsedown.php",
            "MyParsedown"                   => "app/ThirdParty/Parsedown/MyParsedown.php",
            "SimpleImage"                   => "app/ThirdParty/SimpleImage.php",
            "URLScraper"                    => "app/ThirdParty/URLScraper.php",
            "JacksonJeans\MailException"    => "app/ThirdParty/php-mail/MailException.class.php",
            "JacksonJeans\Mail"             => "app/ThirdParty/php-mail/Mail.class.php",
        ];

    }
}

