<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationModel, ActionModel, AnswerModel, PostModel};
use Content, Validation, UserData, Html;

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
        Html::pageError404($post);

        $content = $_POST['content']; // для Markdown

        $url_post = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        Validation::Length($content, 'msg.content', '6', '5000', $url_post);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($content, 'answer');

        $last_id = AnswerModel::add(
            [
                'answer_post_id'    => $post_id,
                'answer_content'    => $content,
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
            (new \App\Controllers\AuditController())->create('answer', $last_id, url('admin.audits'));
        }

        // Notification (@login). 11 - mentions in answers 
        if ($message = Content::parseUser($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_ANSWER, $message, $url, $post['post_user_id']);
        }

        // Кто подписан на данный вопрос / пост
        if ($focus_all = PostModel::getFocusUsersPost($post['post_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $this->user['id']) {
                    NotificationModel::send(
                        [
                            'sender_id'    => $this->user['id'],
                            'recipient_id' => $focus_user['signed_user_id'],
                            'action_type'  => NotificationModel::TYPE_AMSWER_POST,
                            'url'          => $url,
                        ]
                    );
                }
            }
        }

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $last_id,
                'action_type'   => 'answer',
                'action_name'   => 'content_added',
                'url_content'   => $url,
            ]
        );

        redirect($url);
    }
}
