<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\AnswerModel;
use Content, Translate, Tpl;

class AnswerController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Все ответы
    public function index($type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = AnswerModel::getAnswersAllCount('user');
        $answ       = AnswerModel::getAnswersAll($page, $this->limit, $this->user, 'user');

        $result = [];
        foreach ($answ  as $ind => $row) {
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['date']            = lang_date($row['answer_date']);
            $result[$ind]           = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('answers'),
        ];

        return Tpl::agRender(
            '/answer/answers',
            [
                'meta'  => meta($m, Translate::get('all answers'), Translate::get('answers-desc')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'answers',
                    'type'          => $type,
                    'answers'       => $result,
                ]
            ]
        );
    }
}
