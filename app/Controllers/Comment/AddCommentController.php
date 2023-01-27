<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PostPresence;
use App\Services\Сheck\AnswerPresence;
use App\Models\{NotificationModel, ActionModel, AnswerModel, CommentModel};
use App\Validate\Validator;

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

        $answer = AnswerPresence::index($answer_id);

        $post   = PostPresence::index($answer['answer_post_id'], 'id');

        $url_post = post_slug($post['post_id'], $post['post_slug']);

        Validator::Length($content = $_POST['comment'], 6, 2024, 'content', $url_post);

        // Let's check the stop words, url
        // Проверим стоп слова, url
        $trigger = (new \App\Services\Audit())->prohibitedContent($content);

        $last_id = CommentModel::add($post['post_id'], $answer_id, $comment_id, $content, $trigger);

        // Add an audit entry and an alert to the admin
        // Аудит и оповещение персоналу
        if ($trigger === false) {
            (new \App\Services\Audit())->create('comment', $last_id, url('admin.audits'));
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
            NotificationModel::send($answ['answer_user_id'], NotificationModel::TYPE_COMMENT_ANSWER, $url);
        }

        if ($comment_id) {
            $comment = CommentModel::getCommentsId($comment_id);
            if ($this->user['id'] != $comment['comment_user_id']) {
                NotificationModel::send($comment['comment_user_id'], NotificationModel::TYPE_COMMENT_COMMENT, $url);
            }
        }

        // Contact via @
        // Обращение через @
        if ($message = \App\Services\Parser\Content::parseUsers($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_COMMENT, $message, $url, $comment['comment_user_id']);
        }
    }
}
