<?php

namespace App\Controllers\Answer;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\ActionModel;
use App\Models\AnswerModel;
use App\Models\PostModel;
use Lori\Content;
use Lori\Base;

class AddAnswerController extends \MainController
{
    public function index()
    {
        $post_id    = \Request::getPostInt('post_id');
        $post       = PostModel::getPostId($post_id);
        Base::PageError404($post);

        $answer_content = $_POST['answer'];                 // не фильтруем (для Markdown)
        $ip             = \Request::getRemoteAddress();
        $uid            = Base::getUid();

        $redirect = '/post/' . $post['post_id'] . '/' . $post['post_slug'];
        Base::Limits($answer_content, lang('Bodies'), '6', '5000', $redirect);

        Content::stopContentQuietМode($uid);

        // Ограничим частоту добавления (зависит от TL)
        if ($uid['trust_level'] < 2) {
            $num_answer =  AnswerModel::getAnswerSpeed($uid['id']);
            if (count($num_answer) > 10) {
                Base::addMsg(lang('limit_answer_day'), 'error');
                redirect('/');
            }
        }

        $answer_published = 1;
        if (Content::stopWordsExists($answer_content)) {
            // Если меньше 2 ответов и если контент попал в стоп лист, то заморозка
            $all_count = ActionModel::ceneralContributionCount($uid['id']);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($uid['id']);
                Base::addMsg(lang('limiting_mode_1'), 'error');
                redirect('/');
            }

            $answer_published = 0;
            Base::addMsg(lang('answer_audit'), 'error');
        }

        $answer_content = Content::change($answer_content);

        $data = [
            'answer_post_id'    => $post_id,
            'answer_content'    => $answer_content,
            'answer_published'  => $answer_published,
            'answer_ip'         => $ip,
            'answer_user_id'    => $uid['id'],
        ];

        $last_id    = AnswerModel::addAnswer($data);
        $url_answer = $redirect . '#answer_' . $last_id;

        // Оповещение админу
        if ($answer_published == 0) {
            ActionModel::addAudit('answer', $uid['id'], $last_id);
            $type = 15; // Упоминания в посте  
            $user_id  = 1;
            NotificationsModel::send($uid['id'], $user_id, $type, $last_id, $url_answer, 1);
        }

        // Уведомление (@login)
        if ($message = Content::parseUser($answer_content, true, true)) {
            foreach ($message as $user_id) {
                // Запретим отправку себе
                if ($user_id == $uid['id']) {
                    continue;
                }
                $type = 11; // Упоминания в ответе      
                NotificationsModel::send($uid['id'], $user_id, $type, $last_id, $url_answer, 1);
            }
        }
        
        // Кто подписан на данный вопрос / пост
        if ($focus_all = NotificationsModel::getFocusUsersPost($post['post_id'])) {
            
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $uid['id']) {
                    $type = 3; // Ответ на пост
                    NotificationsModel::send($uid['id'], $focus_user['signed_user_id'], $type, $last_id, $url_answer, 1);
                }
            }
            
        }

        // Пересчитываем количество ответов для поста + 1
        PostModel::updateCount($post_id, 'answers');

        redirect($url_answer);
    }
}
