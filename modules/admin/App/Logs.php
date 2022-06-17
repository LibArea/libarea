<?php

namespace Modules\Admin\App;

use Modules\Admin\App\Models\LogModel;
use App\Models\SearchModel;
use App\Controllers\Controller;
use Meta;

class Logs extends Controller
{
    protected $type = 'logs';

    protected $limit = 55;

    // Member activity log
    // Журнал логов действий участников
    public function index()
    {
        $logs       = LogModel::getLogs($this->pageNumber, $this->limit);
        $pagesCount = LogModel::getLogsCount();

        return view(
            '/view/default/logs/index',
            [
                'meta'  => Meta::get(__('admin.logs')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
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
                    'logs' => SearchModel::getSearchLogs(100),
                ]
            ]
        );
    }
    
}
