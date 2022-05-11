<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\AnswerModel;
use Tpl, Meta, UserData;

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
    public function index($sheet)
    {
        $pageNumber = Tpl::pageNumber();

        $pagesCount = AnswerModel::getAnswersCount($sheet);
        $answers    = AnswerModel::getAnswers($pageNumber, $this->limit, $this->user, $sheet);

        $m = [
            'og'    => false,
            'url'   => url('answers'),
        ];

        return Tpl::LaRender(
            '/answer/answers',
            [
                'meta'  => Meta::get(__('app.all_answers'), __('app.answers.desc'), $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'sheet'         => $sheet,
                    'type'          => 'answers',
                    'answers'       => $answers,
                ]
            ]
        );
    }
}
