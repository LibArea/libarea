<?php

namespace App\Controllers\Answer;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PostPresence;
use App\Services\Сheck\AnswerPresence;
use App\Models\AnswerModel;
use App\Models\User\UserModel;
use App\Validate\Validator;
use Meta, Access;

use App\Traits\Author;

class EditAnswerController extends Controller
{
    use Author;

    // Edit form answer
    public function index()
    {
        $answer = AnswerPresence::index(Request::getInt('id'));
        if (Access::author('answer', $answer) == false) {
            return false;
        }

        $post = PostPresence::index($answer['answer_post_id'], 'id');

        return $this->render(
            '/answer/edit',
            [
                'meta'  => Meta::get(__('app.edit_answer')),
                'data'  => [
                    'post'      => $post,
                    'answer'    => $answer,
                    'user'      => UserModel::getUser($answer['answer_user_id'], 'id'),
                    'sheet'     => 'edit-answers',
                    'type'      => 'answer',
                ]
            ]
        );
    }

    public function change()
    {
        $answer_id  = Request::getPostInt('answer_id');
        $content    = $_POST['content']; // для Markdown

        // Access check
        $answer = AnswerModel::getAnswerId($answer_id);

        if (Access::author('answer', $answer) == false) {
            return false;
        }

        $post = PostPresence::index($answer['answer_post_id'], 'id');

        $url_post = post_slug($answer['answer_post_id'], $post['post_slug']);

        Validator::Length($content, 6, 5000, 'content', url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]));

        AnswerModel::edit(
            [
                'answer_id'         => $answer['answer_id'],
                'answer_content'    => $content,
                'answer_user_id'    => $this->selectAuthor($answer['answer_user_id'], Request::getPost('user_id')),
                'answer_modified'   => date("Y-m-d H:i:s"),
            ]
        );

        is_return(__('msg.change_saved'), 'success', $url_post . '#answer_' . $answer['answer_id']);
    }
}
