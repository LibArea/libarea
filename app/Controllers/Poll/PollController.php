<?php

declare(strict_types=1);

namespace App\Controllers\Poll;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\PollModel;
use Meta, Html;

use App\Traits\Poll;

class PollController extends Controller
{
    use Poll;

    protected $limit = 15;

    public function index()
    {
        $polls      = PollModel::getUserQuestionsPolls(Html::pageNumber(), $this->limit);
        $pagesCount = PollModel::getUserQuestionsPollsCount();

        return render(
            '/poll/index',
            [
                'meta'      => Meta::get(__('app.polls')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'type'          => 'polls',
                    'polls'         => $polls
                ]
            ]
        );
    }

    public function poll()
    {
        $id = Request::param('id')->asInt();

        return render(
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
        $question_id = Request::post('question_id')->asInt();
        $answer_id = Request::post('answer_id')->asInt();

        return PollModel::vote($question_id, $answer_id);
    }
}
