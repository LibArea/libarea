<?php

namespace App\Controllers\Answer;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PostPresence;
use App\Models\{NotificationModel, ActionModel, AnswerModel, PostModel};
use App\Validate\Validator;

class AddAnswerController extends Controller
{
    public function create()
    {
        $post = PostPresence::index(Request::getPostInt('post_id'), 'id');

        $url_post = post_slug($post['post_id'], $post['post_slug']);

        Validator::Length($content = $_POST['content'], 6, 5000, 'content', $url_post);

        // Let's check the stop words, url
        // Проверим стоп слова и url
        $trigger = (new \App\Services\Audit())->prohibitedContent($content);

        $last_id = AnswerModel::add($post['post_id'], $content, $trigger);

        // Add an audit entry and an alert to the admin
        // Аудит и оповещение персоналу
        if ($trigger === false) {
            (new \App\Services\Audit())->create('answer', $last_id, url('admin.audits'));
        }

        $url = $url_post . '#answer_' . $last_id;

        $this->notif($content, $post, $url);

        ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => 'answer',
                'action_name'   => 'added',
                'url_content'   => $url,
            ]
        );

        redirect($url);
    }

    // Notifications when adding a answer
    // Уведомления при добавлении ответа
    public function notif($content, $post, $url)
    {
        // Contact via @
        // Обращение через @
        if ($message = \App\Services\Parser\Content::parseUser($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_ANSWER, $message, $url, $post['post_user_id']);
        }

        // Who is following this question/post
        // Кто подписан на данный вопрос / пост
        if ($focus_all = PostModel::getFocusUsersPost($post['post_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $this->user['id']) {
                    NotificationModel::send($focus_user['signed_user_id'], NotificationModel::TYPE_AMSWER_POST, $url);
                }
            }
        }
    }
}
