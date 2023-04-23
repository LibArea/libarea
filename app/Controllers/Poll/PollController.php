<?php

namespace App\Controllers\Poll;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\PollModel;
use Meta;

use App\Traits\Poll;

class PollController extends Controller
{
    use Poll;

    protected $limit = 15;

    public function index()
    {
        $polls      = PollModel::getUserQuestionsPolls($this->pageNumber, $this->limit);
        $pagesCount = PollModel::getUserQuestionsPollsCount();

        return $this->render(
            '/poll/index',
            [
                'meta'      => Meta::get(__('app.polls')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'type'          => 'polls',
                    'polls'         => $polls
                ]
            ]
        );
    }

    public function poll()
    {
        $id = Request::getInt('id');

        return $this->render(
            '/poll/view',
            [
                'meta'  => Meta::get(__('app.poll')),
                'data'  => [
                    'type'      => 'poll',
                    'poll'      => $this->getPoll($id),
                ]
            ]
        );
    }

    public function vote()
    {
        $question_id = Request::getPostInt('question_id');
        $answer_id = Request::getPostInt('answer_id');

        return PollModel::vote($question_id, $answer_id);
    }
}
