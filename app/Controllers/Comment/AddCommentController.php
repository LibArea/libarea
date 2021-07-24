<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\ActionModel;
use App\Models\AnswerModel;
use App\Models\CommentModel;
use App\Models\PostModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class AddCommentController extends \MainController
{

    // Добавление комментария
    public function index()
    {
        $comment_content    = \Request::getPost('comment');
        $post_id            = \Request::getPostInt('post_id');   // в каком посту ответ
        $answer_id          = \Request::getPostInt('answer_id');   // на какой ответ
        $comment_id         = \Request::getPostInt('comment_id');   // на какой комментарий
        
        $uid        = Base::getUid();
        $ip         = \Request::getRemoteAddress(); 
        $post       = PostModel::getPostId($post_id);
        Base::PageError404($post);

        $redirect = '/post/' . $post['post_id'] . '/' . $post['post_slug'];

        // Проверяем длину тела
        Base::Limits($comment_content, lang('Comments-m'), '6', '2024', $redirect);

        if ($uid['limiting_mode'] == 1) {
            Base::addMsg(lang('limiting_mode_1'), 'error');
            redirect('/');
        }

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении комментариев
        if ($uid['trust_level'] < Config::get(Config::PARAM_TL_ADD_COMM)) {
            $num_comm =  CommentModel::getCommentSpeed($uid['id']);
            if (count($num_comm) > 9) {
                Base::addMsg(lang('limit_comment_day'), 'error');
                redirect('/');
            }
        }

        $comment_published = 1;
        if(Content::stopWordsExists($comment_content)) 
        {
            // Если меньше 2 комментариев и если контент попал в стоп лист, то заморозка
            $all_count = ActionModel::ceneralContributionCount($uid['id']);
            if ($all_count < 2) 
            {
                ActionModel::addLimitingMode($uid['id']);
                Base::addMsg(lang('limiting_mode_1'), 'error');
                redirect('/');
            }
            
            $comment_published = 0;
            Base::addMsg(lang('comment_audit'), 'error');
        }

        $data = [
            'comment_post_id'       => $post_id,
            'comment_answer_id'     => $answer_id, 
            'comment_comment_id'    => $comment_id,
            'comment_content'       => $comment_content,
            'comment_published'     => $comment_published,
            'comment_ip'            => $ip,
            'comment_user_id'       => $uid['id'],
        ];

        $last_comment_id    = CommentModel::addComment($data);
        $url_comment        = $redirect . '#comment_' . $last_comment_id; 

        if ($comment_published == 0) {
            ActionModel::addAudit('comment', $uid['id'], $last_comment_id);
            // Оповещение админу
            $type = 15; // Упоминания в посте  
            $user_id  = 1; // админу
            NotificationsModel::send($uid['id'], $user_id, $type, $last_comment_id, $url_comment, 1);
        } 

        // Пересчитываем количество комментариев для поста + 1
        PostModel::updateCount($post_id, 'comments');
        
        // Оповещение автору ответа, что есть комментарий
        if ($answer_id) {
            // Себе не записываем (перенести в общий, т.к. ничего для себя не пишем в notf)
            $answ = AnswerModel::getAnswerId($answer_id);
            if ($uid['id'] != $answ['answer_user_id']) {
                $type = 4; // Ответ на пост        
                NotificationsModel::send($uid['id'], $answ['answer_user_id'], $type, $last_comment_id, $url_comment, 1);
            }
        }
        
        // Уведомление (@login)
        if ($message = Content::parseUser($comment, true, true)) 
        {
            foreach ($message as $user_id) {
                // Запретим отправку себе и автору ответа (оповщение ему выше)
                if ($user_id == $uid['id'] || $user_id == $answ['answer_user_id']) {
                    continue;
                }
                $type = 12; // Упоминания в комментарии      
                NotificationsModel::send($uid['id'], $user_id, $type, $last_comment_id, $url_comment, 1);
            }
        }
        
        redirect($url_comment); 
    }

	// Покажем форму
	public function add()
	{
        $post_id    = \Request::getPostInt('post_id');
        $answer_id  = \Request::getPostInt('answer_id');
        $comment_id = \Request::getPostInt('comment_id');
        
        $uid  = Base::getUid();
        $data = [
            'answer_id'     => $answer_id,
            'post_id'       => $post_id,
            'comment_id'    => $comment_id,
        ]; 
        
        return view(PR_VIEW_DIR . '/comment/add-form-answer-comment', ['data' => $data, 'uid' => $uid]);
    }


}