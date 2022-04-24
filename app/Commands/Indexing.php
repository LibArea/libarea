<?php

namespace App\Commands;

class Indexing extends \Hleb\Scheme\App\Commands\MainTask
{
    const DESCRIPTION = "indexing";

    protected function execute()
    {
        (new \Modules\Admin\App\Console)->indexer();

        echo PHP_EOL . __CLASS__ . " done." . PHP_EOL;
    }
}
