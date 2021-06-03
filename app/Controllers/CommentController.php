<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\AnswerModel;
use App\Models\VotesCommentModel;
use App\Models\NotificationsModel;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use Lori\Config;
use Lori\Base;

class CommentController extends \MainController
{
    // Все комментарии
    public function index()
    {
        $pg = \Request::getInt('page'); 
        $page = (!$pg) ? 1 : $pg;
        
        $uid        = Base::getUid();
        $user_id    = $uid['id'];
         
        $pagesCount = CommentModel::getCommentAllCount();  
        $comm       = CommentModel::getCommentsAll($page, $user_id);

        $result = Array();
        foreach($comm  as $ind => $row){
 
            $row['date']    = Base::ru_date($row['comment_date']);
            // N+1 - перенести в запрос
            $row['comm_vote_status'] = VotesCommentModel::getVoteStatus($row['comment_id'], $user_id);
            $result[$ind]   = $row;
        }
        
        if($page > 1) { 
            $num = ' — ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }
        
        $data = [
            'h1'            => lang('All comments'),
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/comments', 
            'sheet'         => 'comments', 
            'meta_title'    => lang('All comments') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('comments-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/comment/comm-all', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

    // Добавление комментария
    public function createComment()
    {
        // Получим относительный url поста для возрата (упростить)
        $url        = str_replace('//', '', $_SERVER['HTTP_REFERER']);
        $return_url = substr($url, strpos($url, '/') + 1);

        $comment = \Request::getPost('comment');
        
        if (Base::getStrlen($comment) < 6 || Base::getStrlen($comment) > 1024)
        {
            Base::addMsg('Длина комментария должна быть от 6 до 1000 знаков', 'error');
            redirect('/' . $return_url);
            return true;
        }

        $post_id   = \Request::getPostInt('post_id');   // в каком посту ответ
        $answ_id   = \Request::getPostInt('answ_id');   // на какой ответ
        $comm_id   = \Request::getPostInt('comm_id');   // на какой комментарий
        $ip        = \Request::getRemoteAddress();      // ip отвечающего 
        
        // id того, кто отвечает
        $account   = \Request::getSession('account');
        $my_id     = $account['user_id'];
        
        // Ограничим частоту добавления
        // Добавить условие TL
        $num_comm =  CommentModel::getCommentSpeed($my_id);
        if(count($num_comm) > 35) {
            Base::addMsg('Вы исчерпали лимит комментариев (35) на сегодня', 'error');
            redirect('/');
        }
        
        // Записываем коммент
        $last_id = CommentModel::commentAdd($post_id, $answ_id, $comm_id, $ip, $comment, $my_id);
         
        // Адрес комментария 
        $url = $return_url . '#comm_' . $last_id; 
         
        // Добавим в чат и поток
        $data_flow = [
            'flow_action_id'    => 4, // add комментарий
            'flow_content'      => $comment,  
            'flow_user_id'      => $my_id,
            'flow_pubdate'      => date("Y-m-d H:i:s"),
            'flow_url'          => $url,
            'flow_target_id'    => $last_id,
            'flow_about'        => lang('add_comment'),            
            'flow_space_id'     => 0,
            'flow_tl'           => 0,
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);        
         
        // Пересчитываем количество комментариев для поста + 1
        PostModel::getNumComments($post_id);
        
        // Оповещение автору ответа, что есть комментарий
        if($answ_id) {
            // Себе не записываем (перенести в общий, т.к. ничего для себя не пишем в notf)
            $inf_answ = AnswerModel::getAnswerOne($answ_id);
            if($my_id != $inf_answ['answer_user_id']) {
                $type = 4; // Ответ на пост        
                NotificationsModel::send($my_id, $inf_answ['answer_user_id'], $type, $last_id, $url, 1);
            }
        }
        
        // Уведомление (@login)
        if ($message = Base::parseUser($comment, true, true)) {
            
			foreach ($message as $user_id) {
                // Запретим отправку себе и автору ответа (оповщение ему выше)
                if ($user_id == $my_id || $user_id == $inf_answ['answer_user_id']) {
					continue;
				}
 				$type = 12; // Упоминания в комментарии      
                NotificationsModel::send($my_id, $user_id, $type, $last_id, $url, 1);
			}
		}
        
        redirect('/' . $return_url . '#comm_' . $last_id); 
    }
    
    // Редактируем комментарий
    public function editComment()
    {
        $comm_id    = \Request::getPostInt('comm_id');
        $post_id    = \Request::getPostInt('post_id');
        $comment    = \Request::getPost('comment');
        
        // Получим относительный url поста для возрата (упростить)
        $url = str_replace('//', '', $_SERVER['HTTP_REFERER']);
        $return_url = substr($url, strpos($url, '/') + 1);
        
        // id того, кто редактирует
        $uid        = Base::getUid();
        $user_id    = $uid['id'];
        
        $comm = CommentModel::getCommentsOne($comm_id);
        
        // Проверим автора комментария и админа
        if(!$user_id == $comm['comment_user_id']) {
            return true; 
        }
        
        // Редактируем комментарий
        CommentModel::CommentEdit($comm_id, $comment);
        
        redirect('/' . $return_url . '#comm_' . $comm_id); 
	}

   // Покажем форму редактирования
	public function editFormComment()
	{
        $comm_id    = \Request::getPostInt('comm_id');
        $post_id    = \Request::getPostInt('post_id');
         
        // id того, кто редактирует
        $uid        = Base::getUid();
        $user_id    = $uid['id'];
        
        $comm = CommentModel::getCommentsOne($comm_id);

        // Проверим автора комментария и админа
        if($user_id != $comm['comment_user_id'] && $uid['trust_level'] != 5) {
            return true; 
        }

        $data = [
            'comm_id'           => $comm_id,
            'post_id'           => $post_id,
            'user_id'           => $user_id,
            'comment_content'   => $comm['comment_content'],
        ]; 
        
        return view(PR_VIEW_DIR . '/comment/comm-edit-form', ['data' => $data]);
    }

	// Покажем форму ответа
	public function addFormComm()
	{
        $post_id    = \Request::getPostInt('post_id');
        $answ_id    = \Request::getPostInt('answ_id');
        $comm_id    = \Request::getPostInt('comm_id');
        
        $uid  = Base::getUid();
        $data = [
            'answ_id'     => $answ_id,
            'post_id'     => $post_id,
            'comm_id'     => $comm_id,
        ]; 
        
        return view(PR_VIEW_DIR . '/comment/comm-add-form-answ', ['data' => $data, 'uid' => $uid]);
    }

    // Комментарии участника
    public function getUserComments()
    {
        $login = \Request::get('login');

        // Если нет такого пользователя 
        $user   = UserModel::getUserLogin($login);
        Base::PageError404($user);
        
        $comm  = CommentModel::userComments($login); 
        
        $result = Array();
        foreach($comm as $ind => $row){
            $row['date']    = Base::ru_date($row['comment_date']);
            $result[$ind]   = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Комментарии ' . $login,
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/comments', 
            'sheet'         => 'user-comments', 
            'meta_title'    => 'Комментарии ' . $login .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => 'Комментарии ' . $login .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        return view(PR_VIEW_DIR . '/comment/comm-user', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

    // Удаление комментария
    public function deletComment()
    {
        // Доступ только персоналу
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            return false;
        }
        
        $comm_id = \Request::getPostInt('comm_id');
        
        CommentModel::CommentsDel($comm_id);
        
        return false;
    }
    
    // Помещаем комментарий в закладки
    public function addCommentFavorite()
    {
        
        $uid = Base::getUid();
        
        $comm_id = \Request::getPostInt('comm_id');
        $comm    = CommentModel::getCommentsOne($comm_id); 
        
        if(!$comm) {
            redirect('/');
        }
        
        CommentModel::setCommentFavorite($comm_id, $uid['id']);
       
        return true;
    } 
}