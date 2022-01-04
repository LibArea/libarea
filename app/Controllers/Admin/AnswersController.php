<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\AnswerModel;
use Content, Translate;

class AnswersController extends MainController
{
    private $uid;

    protected $limit = 50;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = AnswerModel::getAnswersAllCount($sheet);
        $answers    = AnswerModel::getAnswersAll($page, $this->limit, $this->uid, $sheet);

        $result = [];
        foreach ($answers  as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        return agRender(
            '/admin/answer/answers',
            [
                'meta'  => meta($m = [], $sheet == 'ban' ? Translate::get('deleted answers') : Translate::get('answers')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'answers'       => $result,
                ]
            ]
        );
    }
}
