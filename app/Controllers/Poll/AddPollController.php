<?php

declare(strict_types=1);

namespace App\Controllers\Poll;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{PollModel, ActionModel};
use Meta, Msg;

class AddPollController extends Controller
{
    /**
     * Poll Add Form
     * Форма добавление опроса
     *
     * @return void
     */
    public function index(): void
    {
        render(
            '/poll/add',
            [
                'meta'      => Meta::get(__('app.add_poll')),
                'data'  => [
                    'type'  => 'add',
                ]
            ]
        );
    }

    /**
     * Adding a poll
     * Добавим опрос
     *
     * @return void
     */
    public function add()
    {
        if (!is_array($data = Request::allPost())) {
            return;
        }

        $last_id = PollModel::createQuestion($data['title']);

        foreach ($data as $key => $val) {
            PollModel::createAnswers($key, $val, $last_id);
        }

        ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => 'poll',
                'action_name'   => 'added',
                'url_content'   => url('poll', ['id' => $last_id]),
            ]
        );

        Msg::redirect(__('msg.post_added'), 'success', url('polls'));
    }
}
