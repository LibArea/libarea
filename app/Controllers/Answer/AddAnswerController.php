<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{NotificationsModel, ActionModel, AnswerModel, PostModel};
use Content, Validation, SendEmail, Translate;

class AddAnswerController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    public function create()
    {
        $post_id = Request::getPostInt('post_id');
        $post    = PostModel::getPost($post_id, 'id', $this->uid);
        pageError404($post);

        $answer_content = $_POST['content']; // для Markdown

        $url_post = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        Validation::Limits($answer_content, Translate::get('bodies'), '6', '5000', $url_post);

        // Если пользователь заморожен
        Content::stopContentQuietМode($this->uid['user_limiting_mode']);

        // Ограничим добавления ответов (в день)
        Validation::speedAdd($this->uid, 'answer');

        // Если контента меньше N и он содержит ссылку 
        $answer_published = 1;
        if (!Validation::stopSpam($answer_content, $this->uid['user_id'])) {
            addMsg(Translate::get('content-audit'), 'error');
            $answer_published = 0;
        }

        $last_answer_id = AnswerModel::addAnswer(
            [
                'answer_post_id'    => $post_id,
                'answer_content'    => Content::change($answer_content),
                'answer_published'  => $answer_published,
                'answer_ip'         => Request::getRemoteAddress(),
                'answer_user_id'    => $this->uid['user_id'],
            ]
        );

        // Пересчитываем количество ответов для поста + 1
        PostModel::updateCount($post_id, 'answers');

        $url_answer = $url_post . '#answer_' . $last_answer_id;

        // Оповещение админу
        if ($answer_published == 0) {
            ActionModel::addAudit('answer', $this->uid['user_id'], $last_answer_id);
            NotificationsModel::send(
                [
                    'sender_id'         => $this->uid['user_id'],
                    'recipient_id'      => 1,  // admin
                    'action_type'       => 15, // audit 
                    'connection_type'   => $last_answer_id,
                    'content_url'       => $url_answer,
                ]
            );
        }

        // Уведомление (@login)
        if ($message = Content::parseUser($answer_content, true, true)) {
            foreach ($message as $user_id) {
                // Запретим отправку себе
                if ($user_id == $this->uid['user_id']) {
                    continue;
                }
                NotificationsModel::send(
                    [
                        'sender_id'         => $this->uid['user_id'],
                        'recipient_id'      => $user_id,
                        'action_type'       => 11, // Упоминания в ответе (@login) 
                        'connection_type'   => $last_answer_id,
                        'content_url'       => $url_answer,
                    ]
                );
                SendEmail::mailText($user_id, 'appealed');
            }
        }

        // Кто подписан на данный вопрос / пост
        if ($focus_all = NotificationsModel::getFocusUsersPost($post['post_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $this->uid['user_id']) {
                    NotificationsModel::send(
                        [
                            'sender_id'         => $this->uid['user_id'],
                            'recipient_id'      => $focus_user['signed_user_id'],
                            'action_type'       => 3, // Ответ на пост
                            'connection_type'   => $last_answer_id,
                            'content_url'       => $url_answer,
                        ]
                    );
                }
            }
        }

        ActionModel::addLogs(
            [
                'user_id'           => $this->uid['user_id'],
                'user_login'        => $this->uid['user_login'],
                'log_id_content'    => $last_answer_id,
                'log_type_content'  => 'answer',
                'log_action_name'   => 'content.added',
                'log_url_content'   => $url_answer,
            ]
        );

        redirect($url_answer);
    }
}
