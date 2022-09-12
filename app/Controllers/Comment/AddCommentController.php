<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{NotificationModel, ActionModel, AnswerModel, CommentModel, PostModel};
use App\Validate\Validator;
use Content;

class AddCommentController extends Controller
{
    // Show the form for adding a comment
    // Покажем форму добавление комментария
    public function index()
    {
        insert(
            '/_block/form/add-form-comment',
            [
                'data'  => [
                    'answer_id'  => Request::getPostInt('answer_id'),
                    'comment_id' => Request::getPostInt('comment_id'),
                ],
            ]
        );
    }

    // Adding a comment
    public function create()
    {
        $answer_id  = Request::getPostInt('answer_id');   // на какой ответ
        $comment_id = Request::getPostInt('comment_id');  // на какой комментарий

        $answer = AnswerModel::getAnswerId($answer_id);
        self::error404($answer);

        $post   = PostModel::getPost($answer['answer_post_id'], 'id', $this->user);
        self::error404($post);

        $url_post = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);

        Validator::Length($content = $_POST['comment'], 6, 2024, 'content', $url_post);

        // Let's check the stop words, url
        // Проверим стоп слова, url
        $trigger = (new \App\Controllers\AuditController())->prohibitedContent($content);

        $last_id = CommentModel::add($post['post_id'], $answer_id, $comment_id, $content, $trigger);

        // Add an audit entry and an alert to the admin
        // Аудит и оповещение персоналу
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('comment', $last_id, url('admin.audits'));
        }

        $url = $url_post . '#comment_' . $last_id;

        $this->notif($answer_id, $comment_id, $content, $url);

        ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => 'comment',
                'action_name'   => 'added',
                'url_content'   => $url,
            ]
        );

        redirect($url);
    }

    // Notifications when adding a comment
    // Уведомления при добавлении комментария
    public function notif($answer_id, $comment_id, $content, $url)
    {
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

        // Contact via @
        // Обращение через @
        if ($message = Content::parseUser($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_COMMENT, $message, $url, $comment['comment_user_id']);
        }
    }
}
