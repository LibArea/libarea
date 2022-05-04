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
    public function add()
    {
        return [
            "App\Controllers\*"             => "app/Controllers/",
            "Models\*"                      => "app/Models/",
            "App\Middleware\Before\*"       => "app/Middleware/Before/",
            "App\Middleware\After\*"        => "app/Middleware/After/",
            "Modules\*"                     => "modules/",
            "App\Commands\*"                => "app/Commands/",

            // ... or, if a specific class is added,
            // "Phphleb\Debugpan\DPanel"      => "app/ThirdParty/phphleb/debugpan/DPanel.php",

            "DB"                            => "app/Libraries/DB.php",
            "Config"                        => "app/Libraries/Config.php",
            "Translate"                     => "app/Libraries/Translate.php",

            "Content"                       => "app/Libraries/Content.php",
            "UploadImage"                   => "app/Libraries/UploadImage.php",
            "Integration"                   => "app/Libraries/Integration.php",
            "Validation"                    => "app/Libraries/Validation.php",
            "Tpl"                           => "app/Libraries/Tpl.php",
            "Html"                          => "app/Libraries/Html.php",
            "Meta"                          => "app/Libraries/Meta.php",
            "UserData"                      => "app/Libraries/UserData.php",
            "Forms"                         => "app/Libraries/Forms.php",

            // TODO: заменить
            "Parsedown"                     => "app/Libraries/Parsedown/Parsedown.php",
            "MyParsedown"                   => "app/Libraries/Parsedown/MyParsedown.php",
            "SimpleImage"                   => "app/Libraries/SimpleImage.php",
            "URLScraper"                    => "app/Libraries/URLScraper.php",
            "SendEmail"                     => "app/Libraries/SendEmail.php",
        ];
    }
}
