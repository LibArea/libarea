<?php

namespace App\Traits;

use Html;

trait Page
{
    public static function error404($values)
    {
        if (!$values) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        return true;
    }
}


