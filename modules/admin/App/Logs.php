<?php

namespace Modules\Admin\App;

use Modules\Admin\App\Models\LogModel;
use Meta, Tpl;

class Logs
{
    protected $type = 'logs';

    protected $limit = 55;

    // Member activity log
    // Журнал логов действий участников
    public function index()
    {
        $pageNumber = Tpl::pageNumber();

        $logs       = LogModel::getLogs($pageNumber, $this->limit);
        $pagesCount = LogModel::getLogsCount();

        return view(
            '/view/default/logs/index',
            [
                'meta'  => Meta::get(__('admin.logs')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'type'          => 'logs',
                    'logs'          => $logs,
                ]
            ]
        );
    }

    // Search log
    // Журнал логов поиска
    public function logsSearch()
    {
        return view(
            '/view/default/logs/search',
            [
                'meta'  => Meta::get(__('admin.logs')),
                'data'  => [
                    'type' => $this->type,
                    'logs' => (new \Modules\Search\App\Search())->getLogs(100),
                ]
            ]
        );
    }
}
