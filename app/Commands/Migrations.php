<?php

namespace App\Commands;

class Migrations extends \Hleb\Scheme\App\Commands\MainTask
{
    const DESCRIPTION = "migrations";

    protected function execute()
    {
        (new \Modules\Admin\App\Console)->migrations();

        echo PHP_EOL . __CLASS__ . " done." . PHP_EOL;
    }
}
