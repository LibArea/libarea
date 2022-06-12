<?php

namespace App\Controllers\Answer;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{AnswerModel, PostModel};
use App\Models\User\UserModel;
use Validation, Meta, Access, UserData;

class EditAnswerController extends Controller
{
    // Форма редактирования answer
    public function index()
    {
        $answer_id  = Request::getInt('id');
        $answer = AnswerModel::getAnswerId($answer_id);
        if (Access::author('answer', $answer['answer_user_id'], $answer['answer_date'], 30) == false) {
            return false;
        }

        $post = PostModel::getPost($answer['answer_post_id'], 'id', $this->user);
        self::error404($post);

        Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
        Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        return $this->render(
            '/answer/edit-answer',
            'base',
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
        // Проверка доступа
        $answer = AnswerModel::getAnswerId($answer_id);
        if (Access::author('answer', $answer['answer_user_id'], $answer['answer_date'], 30) == false) {
            return false;
        }

        $post = PostModel::getPost($answer['answer_post_id'], 'id', $this->user);
        $url_post = url('post', ['id' => $answer['answer_post_id'], 'slug' => $post['post_slug']]);

        Validation::Length($content, 6, 5000, 'content', url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]));

        $user_id = $answer['answer_user_id'];
        if (UserData::checkAdmin()) {
            $user_new  = Request::getPost('user_id');
            if ($user_new) {
                $answer_user_new = json_decode($user_new, true);
                $user_id = $answer_user_new[0]['id'];
            }
        }

        AnswerModel::edit(
            [
                'answer_id'         => $answer_id,
                'answer_content'    => $content,
                'answer_user_id'    => $user_id,
                'answer_modified'   => date("Y-m-d H:i:s"),
            ]
        );
             
        Validation::comingBack(__('msg.change_saved'), 'success', $url_post . '#answer_' . $answer['answer_id']);
    }
}
