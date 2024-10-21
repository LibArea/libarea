<?php

declare(strict_types=1);

namespace App\Controllers\Poll;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\PollPresence;
use App\Models\PollModel;
use Meta, Msg;

class EditPollController extends Controller
{
    /**
     * Edit form
     * Форма редактирования
     *
     * @return void
     */
    public function index(): void
    {
        $question   = PollPresence::index($id = Request::param('id')->asInt());
        $answers    = PollModel::getAnswers($id);

        $this->checkingEditPermissions($question);

        render(
            '/poll/edit',
            [
                'meta'  => Meta::get(__('app.edit_poll')),
                'data'  => [
                    'type'          => 'edit',
                    'question'      => $question,
                    'answers'       => $answers,
                    'answers_count' => PollModel::getAnswersCount($id)
                ]
            ]
        );
    }

    public function edit()
    {
        $question   = PollPresence::index($id = Request::post('id')->asInt());

        $this->checkingEditPermissions($question);

        $data = Request::allPost();

        PollModel::editTitle($id, $data['title']);

        $is_closed = Request::post('closed')->value() === 'on' ? 1 : 0;
        PollModel::editClosed($id, $is_closed);

        foreach ($data as $key => $title) {
            if (is_int($key)) {
                PollModel::editAnswers($key, $title, $id);
            }
        }

        Msg::redirect(__('msg.change_saved'), 'success', url('poll', ['id' => $id]));
    }

    public function checkingEditPermissions($question)
    {
        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if ($this->container->access()->author('poll', $question) === false) {
            Msg::redirect(__('msg.access_denied'), 'error');
        }

        return true;
    }

    public function deletingVariant(): bool
    {
        return (bool) PollModel::delVariant(Request::post('id')->asInt());
    }
}
