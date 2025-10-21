<?php

declare(strict_types=1);

namespace App\Commands;

use Hleb\Base\Task;

use Modules\Admin\Controllers\ConsoleController;

/** php console refresh-data {arg} **/
class RefreshData extends Task
{
    /**
     * Resource regeneration and data update.
     */
    protected function run(string $arg): int
    {
        match ($arg) {
            'topics' => ConsoleController::topic(),
            'posts' => ConsoleController::post(),
            'up' => ConsoleController::up(),
            'tl' => ConsoleController::tl(),
            'all' => ConsoleController::all(),
            default => throw new \InvalidArgumentException('Wrong argument: ' . $arg)
        };

        return self::SUCCESS_CODE;
    }
}
