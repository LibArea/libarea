<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{AnswerModel, PostModel};
use Content, Validation, Translate;

class EditAnswerController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Форма редактирования answer
    public function index()
    {
        $answer_id  = Request::getInt('id');
        $answer = AnswerModel::getAnswerId($answer_id);
        if (!accessСheck($answer, 'answer', $this->uid, 0, 0)) {
            redirect('/');
        }

        $post = PostModel::getPost($answer['answer_post_id'], 'id', $this->uid);
        pageError404($post);

        Request::getResources()->addBottomStyles('/assets/js/editor/toastui-editor.min.css');
        Request::getResources()->addBottomStyles('/assets/js/editor/dark.css');
        Request::getResources()->addBottomScript('/assets/js/editor/toastui-editor-all.min.js');

        return agRender(
            '/answer/edit-form-answer',
            [
                'meta'  => meta($m = [], Translate::get('edit answer')),
                'uid'   => $this->uid,
                'data'  => [
                    'answer_id' => $answer['answer_id'],
                    'post_id'   => $post['post_id'],
                    'content'   => $answer['answer_content'],
                    'sheet'     => 'edit-answers',
                    'post'      => $post,
                    'type'      => 'answer',
                ]
            ]
        );
    }

    public function edit()
    {
        $answer_id      = Request::getPostInt('answer_id');
        $post_id        = Request::getPostInt('post_id');
        $answer_content = $_POST['content']; // для Markdown
        $post           = PostModel::getPost($post_id, 'id', $this->uid);

        // Если пользователь заморожен
        Content::stopContentQuietМode($this->uid['user_limiting_mode']);

        $answer = AnswerModel::getAnswerId($answer_id);

        // Проверка доступа
        if (!accessСheck($answer, 'answer', $this->uid, 0, 0)) {
            redirect('/');
        }

        $url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        Validation::Limits($answer_content, Translate::get('bodies'), '6', '5000', '/' . $url);

        $answer_content = Content::change($answer_content);

        // Редактируем комментарий
        AnswerModel::AnswerEdit($answer_id, $answer_content);

        redirect('/' . $url . '#answer_' . $answer_id);
    }
}
