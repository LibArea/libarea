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
            "App\Controllers\*" => "app/Controllers/",
            "Models\*" => "app/Models/",
            "App\Middleware\Before\*" => "app/Middleware/Before/",
            "App\Middleware\After\*" => "app/Middleware/After/",
            "Modules\*" => "modules/",
            "App\Commands\*" => "app/Commands/",
            // ... or, if a specific class is added,
            "UrlRecord"     => "app/Libraries/UrlRecord/UrlRecord.php",
            "ImageUpload"   => "app/Libraries/ImageUpload.php", 
            "Lori\Config"   => "app/Libraries/Config.php",  
            "Lori\Base"     => "app/Libraries/Base.php",   
            "Phphleb\Imageresizer\SimpleImage"   => "app/Libraries/SimpleImage.php", 
   
            "DB"            => "database/DB.php",  
            "XdORM\XD"      => "vendor/phphleb/xdorm/XD.php",
            // "Phphleb\Debugpan\DPanel" => "vendor/phphleb/debugpan/DPanel.php",
            // https://github.com/JacksonJeans/php-mail
            "JacksonJeans\MailException" => "app/Libraries/php-mail/src/JacksonJeans/MailException.class.php",
            "JacksonJeans\Mail" => "app/Libraries/php-mail/src/JacksonJeans/Mail.class.php",
        ];

    }
}

