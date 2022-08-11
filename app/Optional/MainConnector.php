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
            "App\Exception\*"               => "app/Libraries/",

            // ... or, if a specific class is added,
            // "Phphleb\Debugpan\DPanel"      => "app/ThirdParty/phphleb/debugpan/DPanel.php",

            "DB"                            => "app/Libraries/DB.php",
            "Configuration"                 => "app/Libraries/Configuration.php",
            "Translate"                     => "app/Libraries/Translate.php",
            
            "Access"                        => "app/Libraries/Access.php",
            "Msg"                           => "app/Libraries/Msg.php",
            "Img"                           => "app/Libraries/Img.php",

            "Curl"                          => "app/Libraries/Curl.php",
            "Google"                        => "app/Libraries/Integration/Google.php",
            "Discord"                       => "app/Libraries/Integration/Discord.php",
            "Yandex"                        => "app/Libraries/Integration/Yandex.php",
            "GitHub"                        => "app/Libraries/Integration/GitHub.php",

            "Content"                       => "app/Libraries/Content.php",
            "UploadImage"                   => "app/Libraries/UploadImage.php",
            "Validation"                    => "app/Libraries/Validation.php",
            "Html"                          => "app/Libraries/Html.php",
            "Meta"                          => "app/Libraries/Meta.php",
            "UserData"                      => "app/Libraries/UserData.php",

            "Jevix"                         => "app/Libraries/Jevix/Jevix.php",
            "Parser"                        => "app/Libraries/Jevix/Parser.php",

            // TODO: заменить
            "URLScraper"                    => "app/Libraries/URLScraper.php",
            "SendEmail"                     => "app/Libraries/SendEmail.php",
        ];
    }
}
