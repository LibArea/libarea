<?php

namespace App\Commands;

class Stats extends \Hleb\Scheme\App\Commands\MainTask
{
    const DESCRIPTION = "stats";

    protected function execute()
    {
        (new \Modules\Admin\App\Console)->up();
        (new \Modules\Admin\App\Console)->topic();

        echo PHP_EOL . __CLASS__ . " done." . PHP_EOL;
    }
}
