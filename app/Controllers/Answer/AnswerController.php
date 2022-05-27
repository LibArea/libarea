<?php

namespace App\Controllers\Answer;

use App\Controllers\Controller;
use App\Models\AnswerModel;
use Meta;

class AnswerController extends Controller
{
    protected $limit = 25;

    // All answers
    // Все ответы
    public function index($sheet)
    {
        $pagesCount = AnswerModel::getAnswersCount($sheet);
        $answers    = AnswerModel::getAnswers($this->pageNumber, $this->limit, $this->user, $sheet);

        $m = [
            'og'    => false,
            'url'   => url('answers'),
        ];

        return $this->render(
            '/answer/answers',
            'base',
            [
                'meta'  => Meta::get(__('meta.all_answers'), __('meta.answers_desc'), $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'sheet'         => $sheet,
                    'type'          => 'answers',
                    'answers'       => $answers,
                ]
            ]
        );
    }
}
