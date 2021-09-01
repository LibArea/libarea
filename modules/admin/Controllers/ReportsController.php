<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\ReportModel;
use App\Models\UserModel;
use Lori\Base;

class ReportsController extends MainController
{
    public function index()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 50;
        $pagesCount = ReportModel::getCount();
        $reports    = ReportModel::get($page, $limit);

        $result = array();
        foreach ($reports as $ind => $row) {
            $row['user']    = UserModel::getUser($row['report_user_id'], 'id');
            $row['date']    = lang_date($row['report_date']);
            $result[$ind]   = $row;
        }

        $meta = [
            'meta_title'    => lang('Reports'),
            'sheet'         => 'reports',
        ];
        
        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'sheet'         => 'reports',
            'reports'       => $result,
        ];

        return view('/report/reports', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Ознакомился
    public function status()
    {
        $report_id  = Request::getPostInt('id');

        ReportModel::setStatus($report_id);

        return true;
    }
}
