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

    protected int $limit = 15;

    /**
     * All surveys for a specific user
     * Все опросы для конкретного пользователя
     *
     * @return void
     */
    public function index()
    {
        $polls      = PollModel::getUserQuestionsPolls(Html::pageNumber(), $this->limit);
        $pagesCount = PollModel::getUserQuestionsPollsCount();

        render(
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

    /**
     * View the survey
     * Просмотр  опроса
     *
     * @return void
     */
    public function poll()
    {
        $id = Request::param('id')->asInt();

        render(
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

    /**
     * Choosing an option in the survey
     * Выбор варианта в опросе
     *
     * @return void
     */
    public function vote(): void
    {
        $question_id = Request::post('question_id')->asInt();
        $answer_id = Request::post('answer_id')->asInt();

        PollModel::vote($question_id, $answer_id);
    }
}
