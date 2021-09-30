<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\Admin\AnswerModel;
use Agouti\{Content, Base};

class AnswersController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 100;
        $pagesCount = AnswerModel::getAnswersAllCount($sheet);
        $answers    = AnswerModel::getAnswersAll($page, $limit, $sheet);

        $result = array();
        foreach ($answers  as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        $meta_title = lang('answers-n');
        if ($sheet == 'ban') {
            $meta_title = lang('deleted answers');
        }

        $meta = [
            'meta_title'    => $meta_title,
            'sheet'         => 'answers',
        ];

        $data = [
            'sheet'         => $sheet == 'all' ? 'answers-n' : 'answers-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'answers'       => $result,
        ];

        return view('/admin/answer/answers', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
