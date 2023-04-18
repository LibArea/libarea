<?php

namespace App\Controllers\Poll;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\PollModel;
use Meta;

class PollController extends Controller
{
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
        $id         = Request::getInt('id');
        $question   = PollModel::getQuestion($id);
        $answers    = PollModel::getAnswers($id);
        $count      = PollModel::getAllVotesCount($question['poll_id']);

        return $this->render(
            '/poll/view',
            [
                'meta'      => Meta::get(__('app.poll')),
                'data'  => [
                    'type'      => 'poll',
                    'question'  => $question,
                    'answers'   => $answers,
                    'count'     => $count['sum'],
                    'isVote'    => PollModel::isVote($question['poll_id'])
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
