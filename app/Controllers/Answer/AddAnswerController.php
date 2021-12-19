<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{NotificationsModel, ActionModel, AnswerModel, PostModel};
use Content, Base, Validation, SendEmail, Translate;

class AddAnswerController extends MainController
{
    public function create()
    {
        $post_id = Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id);
        pageError404($post);

        $answer_content = $_POST['content']; // для Markdown
        $uid            = Base::getUid();

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);
        Content::stopContentQuietМode($user);

        $redirect = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        Validation::Limits($answer_content, Translate::get('bodies'), '6', '5000', $redirect);

        // Ограничим добавления ответов (в день)
        Validation::speedAdd($uid, 'answer');

        // Если контента меньше N и он содержит ссылку 
        // Оповещение админу
        $answer_published = 1;
        if (!Validation::stopSpam($answer_content, $uid['user_id'])) {
            addMsg(Translate::get('content-audit'), 'error');
            $answer_published = 0;
        }

        $answer_content = Content::change($answer_content);

        $data = [
            'answer_post_id'    => $post_id,
            'answer_content'    => $answer_content,
            'answer_published'  => $answer_published,
            'answer_ip'         => Request::getRemoteAddress(),
            'answer_user_id'    => $uid['user_id'],
        ];

        $last_id    = AnswerModel::addAnswer($data);
        $url_answer = $redirect . '#answer_' . $last_id;

        // Оповещение админу
        if ($answer_published == 0) {
            ActionModel::addAudit('answer', $uid['user_id'], $last_id);
            $type       = 15; // Упоминания в посте  
            $user_id    = 1;
            NotificationsModel::send($uid['user_id'], $user_id, $type, $last_id, $url_answer, 1);
        }

        // Уведомление (@login)
        if ($message = Content::parseUser($answer_content, true, true)) {
            foreach ($message as $user_id) {
                // Запретим отправку себе
                if ($user_id == $uid['user_id']) {
                    continue;
                }
                $type = 11; // Упоминания в ответе      
                NotificationsModel::send($uid['user_id'], $user_id, $type, $last_id, $url_answer, 1);
                SendEmail::mailText($user_id, 'appealed');
            }
        }

        // Кто подписан на данный вопрос / пост
        if ($focus_all = NotificationsModel::getFocusUsersPost($post['post_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $uid['user_id']) {
                    $type = 3; // Ответ на пост
                    NotificationsModel::send($uid['user_id'], $focus_user['signed_user_id'], $type, $last_id, $url_answer, 1);
                }
            }
        }

        // Пересчитываем количество ответов для поста + 1
        PostModel::updateCount($post_id, 'answers');

        redirect($url_answer);
    }
}
