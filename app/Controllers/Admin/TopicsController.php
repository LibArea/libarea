<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\TopicModel;
use Base;

class TopicsController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = TopicModel::getTopicsAllCount($uid['user_id'], $sheet);
        $topics     = TopicModel::getTopicsAll($page, $limit, $uid['user_id'], $sheet);

        $meta = meta($m = [], lang('topics'));
        $data = [
            'sheet'         => $sheet == 'all' ? 'topics' : $sheet,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'topics'        => $topics,
        ];

        return view('/admin/topic/topics', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

}
