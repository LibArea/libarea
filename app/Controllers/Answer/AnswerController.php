<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\AnswerModel;
use Translate, Tpl, Meta, UserData;

class AnswerController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // All answers
    // Все ответы
    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = AnswerModel::getAnswersCount($sheet);
        $answers    = AnswerModel::getAnswers($page, $this->limit, $this->user, $sheet);

        $m = [
            'og'    => false,
            'url'   => getUrlByName('answers'),
        ];

        return Tpl::agRender(
            '/answer/answers',
            [
                'meta'  => Meta::get($m, Translate::get('all.answers'), Translate::get('answers-desc')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'answers'       => $answers,
                ]
            ]
        );
    }
}
