<?php

namespace App\Controllers\Poll;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{PollModel, ActionModel};
use Meta;

class AddPollController extends Controller
{
    // Poll Add Form
    // Форма добавление опроса
    public function index()
    {
        return $this->render(
            '/poll/add',
            [
                'meta'      => Meta::get(__('app.add_poll')),
                'data'  => [
                    'type'  => 'add',
                ]
            ]
        );
    }

    // Adding a poll
    // Добавим опрос
    public function create()
    {
        if (!is_array($data = Request::getPost())) {
            return false;
        }

        $last_id = PollModel::createQuestion($data['title']);

        foreach ($data as $key => $val) {
            PollModel::createAnswers($key, $val, $last_id);
        }

        /* ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => 'poll',
                'action_name'   => 'added',
                'url_content'   => $url_content,
            ]
        ); */

        is_return(__('msg.post_added'), 'success', url('polls'));
    }
}
