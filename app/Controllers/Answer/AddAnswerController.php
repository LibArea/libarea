<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationsModel, ActionModel, AnswerModel, PostModel};
use Content, Validation, Translate, UserData;

class AddAnswerController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function create()
    {
        $post_id = Request::getPostInt('post_id');
        $post    = PostModel::getPost($post_id, 'id', $this->user);
        pageError404($post);

        $answer_content = $_POST['content']; // для Markdown

        $url_post = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        Validation::Length($answer_content, Translate::get('bodies'), '6', '5000', $url_post);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($answer_content, 'answer');

        $last_id = AnswerModel::add(
            [
                'answer_post_id'    => $post_id,
                'answer_content'    => Content::change($answer_content),
                'answer_published'  => ($trigger === false) ? 0 : 1,
                'answer_ip'         => Request::getRemoteAddress(),
                'answer_user_id'    => $this->user['id'],
            ]
        );

        // Recalculating the number of responses for the post + 1
        // Пересчитываем количество ответов для поста + 1
        PostModel::updateCount($post_id, 'answers');

        $url = $url_post . '#answer_' . $last_id;

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('answer', $last_id, $url);
        }

        // Notification (@login). 11 - mentions in answers 
        if ($message = Content::parseUser($answer_content, true, true)) {
            (new \App\Controllers\NotificationsController())->mention(11, $message, $last_id, $url);
        }

        // Кто подписан на данный вопрос / пост
        if ($focus_all = NotificationsModel::getFocusUsersPost($post['post_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $this->user['id']) {
                    NotificationsModel::send(
                        [
                            'notification_sender_id'    => $this->user['id'],
                            'notification_recipient_id' => $focus_user['signed_user_id'],
                            'notification_action_type'  => 3, // Ответ на пост 
                            'notification_url'          => $url,
                            'notification_read_flag'    => 0,
                        ]
                    );
                }
            }
        }

        ActionModel::addLogs(
            [
                'log_user_id'       => $this->user['id'],
                'log_user_login'    => $this->user['login'],
                'log_id_content'    => $last_id,
                'log_type_content'  => 'answer',
                'log_action_name'   => 'content.added',
                'log_url_content'   => $url,
                'log_date'          => date("Y-m-d H:i:s"),
            ]
        );

        redirect($url);
    }
}
