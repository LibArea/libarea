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
            "App\Exception\*"               => "app/Exception/",

            // ... or, if a specific class is added,
            // "Phphleb\Debugpan\DPanel" => "vendor/phphleb/debugpan/DPanel.php",

            "DB"                            => "app/Core/DB.php",
            "Configuration"                 => "app/Core/Configuration.php",
            "Translate"                     => "app/Core/Translate.php",
            "Access"                        => "app/Core/Access.php",
            "Msg"                           => "app/Core/Msg.php",
            "Img"                           => "app/Core/Img.php",
            "Curl"                          => "app/Core/Curl.php",
            "Content"                       => "app/Core/Content.php",
            "UploadImage"                   => "app/Core/UploadImage.php",
            "Html"                          => "app/Core/Html.php",
            "Meta"                          => "app/Core/Meta.php",
            "UserData"                      => "app/Core/UserData.php",

            "URLScraper"                    => "app/Core/URLScraper.php",
            "SendEmail"                     => "app/Core/SendEmail.php",
        ];
    }
}
