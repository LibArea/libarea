<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\AnswerModel;
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

        $meta_title = lang('Answers-n');
        if ($sheet == 'ban') {
            $meta_title = lang('Deleted answers');
        }

        $meta = [
            'meta_title'    => $meta_title,
            'sheet'         => 'answers',
        ];

        $data = [
            'sheet'         => $sheet == 'all' ? 'answers' : 'answers-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'answers'       => $result,
        ];

        return view('/answer/answers', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
