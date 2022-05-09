<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationModel, ActionModel, AnswerModel, CommentModel, PostModel};
use Content, Validation, Tpl, Html, UserData;

class AddCommentController extends MainController
{
    protected $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Show the form for adding a comment
    // Покажем форму добавление комментария
    public function index()
    {
        Tpl::insert(
            '/_block/form/add-form-comment',
            [
                'data'  => [
                    'answer_id'  => Request::getPostInt('answer_id'),
                    'comment_id' => Request::getPostInt('comment_id'),
                ],
                'user' => $this->user
            ]
        );
    }

    // Adding a comment
    public function create()
    {
        $content    = $_POST['comment']; // для Markdown
        $answer_id  = Request::getPostInt('answer_id');   // на какой ответ
        $comment_id = Request::getPostInt('comment_id');   // на какой комментарий

        $answer = AnswerModel::getAnswerId($answer_id);
        Html::pageRedirection($answer);

        $post   = PostModel::getPost($answer['answer_post_id'], 'id', $this->user);
        Html::pageRedirection($post);

        $url_post = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);

        Validation::Length($content, 'msg.content', '6', '2024', $url_post);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($content, 'comment');

        $last_id = CommentModel::add(
            [
                'comment_post_id'       => $post['post_id'],
                'comment_answer_id'     => $answer_id,
                'comment_comment_id'    => $comment_id,
                'comment_content'       => $content,
                'comment_published'     => ($trigger === false) ? 0 : 1,
                'comment_ip'            => Request::getRemoteAddress(),
                'comment_user_id'       => $this->user['id'],
            ]
        );

        $url = $url_post . '#comment_' . $last_id;

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('comment', $last_id, url('admin.audits'));
        }

        // Add the number of comments for the post + 1
        PostModel::updateCount($post['post_id'], 'comments');

        // Notification to the author of the answer that there is a comment (do not write to ourselves) 
        // Оповещение автору ответа, что есть комментарий (себе не записываем)
        $answ = AnswerModel::getAnswerId($answer_id);
        if ($this->user['id'] != $answ['answer_user_id']) {
            NotificationModel::send(
                [
                    'sender_id'    => $this->user['id'],
                    'recipient_id' => $answ['answer_user_id'],
                    'action_type'  => NotificationModel::TYPE_COMMENT_ANSWER,
                    'url'          => $url,
                ]
            );
        }

        if ($comment_id) {
            $comment = CommentModel::getCommentsId($comment_id);
            if ($this->user['id'] != $comment['comment_user_id']) {
                NotificationModel::send(
                    [
                        'sender_id'    => $this->user['id'],
                        'recipient_id' => $comment['comment_user_id'],
                        'action_type'  => NotificationModel::TYPE_COMMENT_COMMENT,
                        'url'          => $url,
                    ]
                );
            }
        }

        // Notification (@login). 12 - mentions in comments 
        if ($message = Content::parseUser($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_COMMENT, $message, $url, $comment['comment_user_id']);
        }

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $last_id,
                'action_type'   => 'comment',
                'action_name'   => 'content_added',
                'url_content'   => $url,
            ]
        );

        redirect($url);
    }
}
