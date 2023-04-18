<?php

namespace App\Controllers\Poll;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PollPresence;
use App\Models\{PollModel, ActionModel};
use Access, Meta;

class EditPollController extends Controller
{
    // Edit form
    // Форма редактирования
    public function index()
    {
        $question   = PollPresence::index($id = Request::getInt('id'));
        $answers    = PollModel::getAnswers($id);

        $this->checkingEditPermissions($question);

        return $this->render(
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

    public function change()
    {
        $question   = PollPresence::index($id = Request::getPostInt('poll_id'));

        $this->checkingEditPermissions($question);

        $data = Request::getPost();

        PollModel::editTitle($id, $data['title']);

        foreach ($data as $key => $title) {
            if (is_int($key)) {
                PollModel::editAnswers($key, $title, $id);
            }
        }

        is_return(__('msg.change_saved'), 'success', url('poll', ['id' => $id]));
    }

    public function checkingEditPermissions($question)
    {
        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (Access::author('poll', $question) === false) {
            is_return(__('msg.access_denied'), 'error');
        }

        return true;
    }
}
