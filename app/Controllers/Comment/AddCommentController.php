<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{NotificationsModel, ActionModel, AnswerModel, CommentModel, PostModel};
use Content, Validation, SendEmail, Translate;

class AddCommentController extends MainController
{
    protected $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Покажем форму
    public function index()
    {
        agIncludeTemplate(
            '/_block/form/add-form-answer-and-comment',
            [
                'data'  => [
                    'answer_id'     => Request::getPostInt('answer_id'),
                    'post_id'       => Request::getPostInt('post_id'),
                    'comment_id'    => Request::getPostInt('comment_id'),
                ],
                'uid'   => $this->uid
            ]
        );
    }

    // Добавление комментария
    public function create()
    {
        $comment_content    = Request::getPost('comment');
        $post_id            = Request::getPostInt('post_id');   // в каком посту ответ
        $answer_id          = Request::getPostInt('answer_id');   // на какой ответ
        $comment_id         = Request::getPostInt('comment_id');   // на какой комментарий

        $post   = PostModel::getPost($post_id, 'id', $this->uid);
        pageError404($post);

        $url_post = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);

        // Check body length 
        // Проверяем длину тела
        Validation::Length($comment_content, Translate::get('comments'), '6', '2024', $url_post);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($comment_content, 'comment');       

        $last_comment_id = CommentModel::addComment(
            [
                'comment_post_id'       => $post_id,
                'comment_answer_id'     => $answer_id,
                'comment_comment_id'    => $comment_id,
                'comment_content'       => Content::change($comment_content),
                'comment_published'     => ($trigger === false) ? 0 : 1,
                'comment_ip'            => Request::getRemoteAddress(),
                'comment_user_id'       => $this->uid['user_id'],
            ]
        );
        
        $url_comment    = $url_post . '#comment_' . $last_comment_id;

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            ActionModel::addAudit('comment', $this->uid['user_id'], $last_comment_id, $url_comment);
        }

        // Пересчитываем количество комментариев для поста + 1
        PostModel::updateCount($post_id, 'comments');

        // Оповещение автору ответа, что есть комментарий
        if ($answer_id) {
            // Себе не записываем
            $answ = AnswerModel::getAnswerId($answer_id);
            if ($this->uid['user_id'] != $answ['answer_user_id']) {
                NotificationsModel::send(
                    [
                        'sender_id'         => $this->uid['user_id'],
                        'recipient_id'      => $answ['answer_user_id'],
                        'action_type'       => 4, // comment 
                        'connection_type'   => $last_comment_id,
                        'content_url'       => $url_comment,
                    ]
                );
            }
        }

        // Уведомление (@login)
        if ($message = Content::parseUser($comment_content, true, true)) {
            foreach ($message as $user_id) {
                // Запретим отправку себе и автору ответа (оповщение ему выше)
                if ($user_id == $this->uid['user_id'] || $user_id == $answ['answer_user_id']) {
                    continue;
                }
                NotificationsModel::send(
                    [
                        'sender_id'         => $this->uid['user_id'],
                        'recipient_id'      => $user_id,
                        'action_type'       => 12, // Упоминания в комментарии
                        'connection_type'   => $last_comment_id,
                        'content_url'       => $url_comment,
                    ]
                );
                SendEmail::mailText($user_id, 'appealed');
            }
        }

        ActionModel::addLogs(
            [
                'user_id'           => $this->uid['user_id'],
                'user_login'        => $this->uid['user_login'],
                'log_id_content'    => $last_comment_id,
                'log_type_content'  => 'comment',
                'log_action_name'   => 'content.added',
                'log_url_content'   => $url_comment,
            ]
        );

        redirect($url_comment);
    }
}
