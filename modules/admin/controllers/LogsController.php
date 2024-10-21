<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;
use Modules\Admin\Models\{SearchModel, LogModel};
use Meta, Html;

class LogsController extends Module
{
    protected $type = 'logs';

    protected $limit = 55;

    /**
     * Member activity log
     * Журнал логов действий участников
     */
    public function index(): View
    {
        $logs       = LogModel::getLogs(Html::pageNumber(), $this->limit);
        $pagesCount = LogModel::getLogsCount();

        return view(
            '/logs/index',
            [
                'meta'  => Meta::get(__('admin.logs')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'type'          => 'logs',
                    'logs'          => $logs,
                ]
            ]
        );
    }

    /**
     * Search log
     * Журнал логов поиска
     */
    public function logsSearch(): View
    {
        return view(
            '/logs/search',
            [
                'meta'  => Meta::get(__('admin.logs')),
                'data'  => [
                    'type' => $this->type,
                    'logs' => SearchModel::getSearchLogs(100),
                ]
            ]
        );
    }
}
