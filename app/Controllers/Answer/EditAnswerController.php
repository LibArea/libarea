<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{AnswerModel, PostModel};
use Content, Validation, Tpl, Meta, Html, UserData;

class EditAnswerController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Форма редактирования answer
    public function index()
    {
        $answer_id  = Request::getInt('id');
        $answer = AnswerModel::getAnswerId($answer_id);
        if (!Html::accessСheck($answer, 'answer', 0, 0)) {
            redirect('/');
        }

        $post = PostModel::getPost($answer['answer_post_id'], 'id', $this->user);
        Html::pageError404($post);

        Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
        Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');

        return Tpl::LaRender(
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

    public function edit()
    {
        $answer_id  = Request::getPostInt('answer_id');
        $content    = $_POST['content']; // для Markdown

        // If the user is frozen
        // Если пользователь заморожен
        (new \App\Controllers\AuditController())->stopContentQuietМode($this->user['limiting_mode']);

        // Access check
        // Проверка доступа
        $answer = AnswerModel::getAnswerId($answer_id);
        if (!Html::accessСheck($answer, 'answer', 0, 0)) {
            redirect('/');
        }

        $post = PostModel::getPost($answer['answer_post_id'], 'id', $this->user);
        $url = url('post', ['id' => $answer['answer_post_id'], 'slug' => $post['post_slug']]);
        Validation::Length($content, 'content', '6', '5000', '/' . $url);

        AnswerModel::edit(
            [
                'answer_id'         => $answer_id,
                'answer_content'    => Content::change($content),
                'answer_modified'   => date("Y-m-d H:i:s"),
            ]
        );

        redirect('/' . $url . '#answer_' . $answer_id);
    }
}
