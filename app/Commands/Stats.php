<?php

namespace App\Commands;

use App\Models\Admin\СonsoleModel;

class Stats extends \Hleb\Scheme\App\Commands\MainTask
{
    const DESCRIPTION = "stats";

    protected function execute($arg = null) {

        (new \App\Controllers\Admin\СonsoleController)->updateCountPostTopic();
        (new \App\Controllers\Admin\СonsoleController)->updateCountUp();
  
        echo PHP_EOL . __CLASS__ . " done." . PHP_EOL;
    }
}