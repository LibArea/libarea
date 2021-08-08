<?php

namespace App\Controllers\Answer;

use Hleb\Constructor\Handlers\Request;
use App\Models\AnswerModel;
use App\Models\PostModel;
use App\Models\UserModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class EditAnswerController extends \MainController
{
    // Редактируем ответ
    public function index()
    {
        $answer_id      = \Request::getPostInt('answer_id');
        $post_id        = \Request::getPostInt('post_id');
        $answer_content = $_POST['answer']; // не фильтруем

        $post = PostModel::getPostId($post_id);

        // Получим относительный url поста для возрата
        $url = '/post/' . $post['post_id'] . '/' . $post['post_slug'];

        // id того, кто редактирует
        $uid        = Base::getUid();
        $user_id    = $uid['id'];

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($uid['id'], 'id');
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        $answer = AnswerModel::getAnswerId($answer_id);

        // Проверка доступа
        if (!accessСheck($answer, 'answer', $uid, 0, 0)) {
            redirect('/');
        }

        Base::Limits($answer_content, lang('Bodies'), '6', '5000', '/' . $url);

        $answer_content = Content::change($answer_content);

        // Редактируем комментарий
        AnswerModel::AnswerEdit($answer_id, $answer_content);

        redirect('/' . $url . '#answer_' . $answer_id);
    }

    // Покажем форму
    public function edit()
    {
        $answer_id  = \Request::getInt('id');
        $post_id    = \Request::getInt('post_id');
        $uid        = Base::getUid();

        $answer = AnswerModel::getAnswerId($answer_id);

        // Проверка доступа 
        if (!accessСheck($answer, 'answer', $uid, 0, 0)) {
            redirect('/');
        }

        $post = PostModel::getPostId($answer['answer_post_id']);
        Base::PageError404($post);

        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/editormd.js');
        Request::getResources()->addBottomScript('/assets/editor/config.js');

        $data = [
            'h1'                => lang('Edit answer'),
            'answer_id'         => $answer['answer_id'],
            'post_id'           => $post['post_id'],
            'user_id'           => $uid['id'],
            'answer_content'    => $answer['answer_content'],
            'sheet'             => 'edit-answers',
            'meta_title'        => lang('Edit answer') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/answer/edit-form-answer', ['data' => $data, 'uid' => $uid, 'post' => $post]);
    }
}
