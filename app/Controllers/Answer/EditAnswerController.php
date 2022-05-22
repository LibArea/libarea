<?php

namespace App\Controllers\Answer;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{AnswerModel, PostModel};
use Validation, Meta, Html, Access;

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
        Html::pageError404($post);

        Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
        Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');

        return $this->render(
            '/answer/edit-answer',
            [
                'meta'  => Meta::get(__('app.edit_answer')),
                'data'  => [
                    'answer_id' => $answer['answer_id'],
                    'post_id'   => $post['post_id'],
                    'content'   => preg_replace('/</', '', $answer['answer_content']),
                    'sheet'     => 'edit-answers',
                    'post'      => $post,
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
        $url = url('post', ['id' => $answer['answer_post_id'], 'slug' => $post['post_slug']]);
        
        if (!Validation::length($content, 6, 5000)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.content') . '»'])]);
        }
        
        AnswerModel::edit(
            [
                'answer_id'         => $answer_id,
                'answer_content'    => $content,
                'answer_modified'   => date("Y-m-d H:i:s"),
            ]
        );

        return true;
    }
}
