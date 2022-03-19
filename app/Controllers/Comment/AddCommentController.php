<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationModel, ActionModel, AnswerModel, CommentModel, PostModel};
use Content, Validation, Translate, Tpl, UserData;

class AddCommentController extends MainController
{
    protected $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Покажем форму
    public function index()
    {
        Tpl::agIncludeTemplate(
            '/_block/form/add-form-answer-and-comment',
            [
                'data'  => [
                    'answer_id'     => Request::getPostInt('answer_id'),
                    'post_id'       => Request::getPostInt('post_id'),
                    'comment_id'    => Request::getPostInt('comment_id'),
                ],
                'user'   => $this->user
            ]
        );
    }

    // Добавление комментария
    public function create()
    {
        $content    = $_POST['comment']; // для Markdown
        $post_id    = Request::getPostInt('post_id');   // в каком посту ответ
        $answer_id  = Request::getPostInt('answer_id');   // на какой ответ
        $comment_id = Request::getPostInt('comment_id');   // на какой комментарий

        $post   = PostModel::getPost($post_id, 'id', $this->user);
        pageError404($post);

        $url_post = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);

        Validation::Length($content, Translate::get('comments'), '6', '2024', $url_post);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($content, 'comment');

        $last_id = CommentModel::add(
            [
                'comment_post_id'       => $post_id,
                'comment_answer_id'     => $answer_id,
                'comment_comment_id'    => $comment_id,
                'comment_content'       => Content::change($content),
                'comment_published'     => ($trigger === false) ? 0 : 1,
                'comment_ip'            => Request::getRemoteAddress(),
                'comment_user_id'       => $this->user['id'],
            ]
        );

        $url = $url_post . '#comment_' . $last_id;

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('comment', $last_id, $url);
        }

        // Add the number of comments for the post + 1
        PostModel::updateCount($post_id, 'comments');

        // Notification to the author of the answer that there is a comment (do not write to ourselves) 
        // Оповещение автору ответа, что есть комментарий (себе не записываем)
        $answ = AnswerModel::getAnswerId($answer_id);
        $recipient_id = $answ['answer_user_id'];
        if ($this->user['id'] != $recipient_id) {
            NotificationModel::send(
                [
                    'sender_id'    => $this->user['id'],
                    'recipient_id' => $recipient_id,
                    'action_type'  => NotificationModel::TYPE_COMMENT_ANSWER,
                    'url'          => $url,
                ]
            );
        }

        // Notification (@login). 12 - mentions in comments 
        if ($message = Content::parseUser($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_COMMENT, $message, $last_id, $url, $recipient_id);
        }

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $last_id,
                'action_type'   => 'comment',
                'action_name'   => 'content.added',
                'url_content'   => $url,
            ]
        );

        redirect($url);
    }
}
