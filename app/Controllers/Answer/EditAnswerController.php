<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{AnswerModel, PostModel};
use Content, Config, Base, Validation;

class EditAnswerController extends MainController
{
    private $uid;
    
    public function __construct() 
    {
        $this->uid  = Base::getUid();
    }
    
    // Редактируем ответ
    public function index()
    {
        $answer_id      = Request::getPostInt('answer_id');
        $post_id        = Request::getPostInt('post_id');
        $answer_content = $_POST['answer']; // для Markdown
        $post           = PostModel::getPostId($post_id);

        // Если кто редактирует забанен / заморожен
        $user   = UserModel::getUser($this->uid['user_id'], 'id');
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        $answer = AnswerModel::getAnswerId($answer_id);

        // Проверка доступа
        if (!accessСheck($answer, 'answer', $this->uid, 0, 0)) {
            redirect('/');
        }
        
        $url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        Validation::Limits($answer_content, lang('bodies'), '6', '5000', '/' . $url);

        $answer_content = Content::change($answer_content);

        // Редактируем комментарий
        AnswerModel::AnswerEdit($answer_id, $answer_content);

        redirect('/' . $url . '#answer_' . $answer_id);
    }

    // Покажем форму
    public function edit()
    {
        // Проверка доступа 
        $answer_id  = Request::getInt('id');
        $answer = AnswerModel::getAnswerId($answer_id);
        if (!accessСheck($answer, 'answer', $this->uid, 0, 0)) {
            redirect('/');
        }

        $post = PostModel::getPostId($answer['answer_post_id']);
        Base::PageError404($post);

        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/meditor.min.js');
 
        $meta = meta($m =[], lang('edit answer']);
        $data = [
            'answer_id'         => $answer['answer_id'],
            'post_id'           => $post['post_id'],
            'answer_content'    => $answer['answer_content'],
            'sheet'             => 'edit-answers',
            'post'              => $post,
        ];

        return view('/answer/edit-form-answer', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }
}
