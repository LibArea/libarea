<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{ReportModel, ActionModel};
use Translate, Tpl;

class ReportsController extends MainController
{
    protected $limit = 20;

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = ReportModel::getCount();
        $reports    = ReportModel::get($page, $this->limit);

        $result = [];
        foreach ($reports as $ind => $row) {
            $row['user']    = UserModel::getUser($row['report_user_id'], 'id');
            $row['date']    = lang_date($row['report_date']);
            $result[$ind]   = $row;
        }

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return Tpl::agRender(
            '/admin/report/reports',
            [
                'meta'  => meta($m = [], Translate::get('reports')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'type'          => $type,
                    'sheet'         => $sheet,
                    'reports'       => $result,
                ]
            ]
        );
    }

    public function logs($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        Request::getResources()->addBottomScript('/assets/js/admin.js');
        $pagesCount = 1;

        $logs       = ActionModel::getLogs($page, $this->limit);
        $pagesCount = ActionModel::getLogsCount();

        return Tpl::agRender(
            '/admin/report/logs',
            [
                'meta'  => meta($m = [], Translate::get('logs')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'type'          => $type,
                    'sheet'         => $sheet,
                    'logs'          => $logs,
                ]
            ]
        );
    }

    // Ознакомился
    public function status()
    {
        $report_id  = Request::getPostInt('id');

        ReportModel::setStatus($report_id);

        return true;
    }
}
