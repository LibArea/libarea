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
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $uid    = Base::getUid();
         
        $pagesCount = CommentModel::getCommentAllCount();  
        $comm       = CommentModel::getCommentsAll($page, $uid['id'], $uid['trust_level']);

        $result = Array();
        foreach($comm  as $ind => $row){
 
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Base::text($row['comment_content'], 'line');
            $row['comm_vote_status']    = VotesCommentModel::getVoteStatus($row['comment_id'], $uid['id']);
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
        $comment    = \Request::getPost('comment');
        $post_id    = \Request::getPostInt('post_id');   // в каком посту ответ
        $answ_id    = \Request::getPostInt('answ_id');   // на какой ответ
        $comm_id    = \Request::getPostInt('comm_id');   // на какой комментарий
        
        $uid        = Base::getUid();
        $ip         = \Request::getRemoteAddress(); 
        
        $post       = PostModel::postId($post_id);
        Base::PageError404($post);

        $redirect = '/post/' . $post['post_id'] . '/' . $post['post_slug'];

        if (Base::getStrlen($comment) < 6 || Base::getStrlen($comment) > 1024)
        {
            Base::addMsg('Длина комментария должна быть от 6 до 1000 знаков', 'error');
            redirect($redirect);
            return true;
        }
        
        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении комментариев
        if($uid['trust_level'] < Config::get(Config::PARAM_TL_ADD_COMM)) {
            $num_comm =  CommentModel::getCommentSpeed($uid['id']);
            if(count($num_comm) > 9) {
                Base::addMsg('Вы исчерпали лимит комментариев (15) на сегодня', 'error');
                redirect('/');
            }
        }
        
        // Записываем коммент и получаем его url
        $last_comment_id    = CommentModel::commentAdd($post_id, $answ_id, $comm_id, $ip, $comment, $uid['id']);
        $url_comment        = $redirect . '#comm_' . $last_comment_id; 
         
        // Добавим в чат и поток
        $data_flow = [
            'flow_action_id'    => 4, // add комментарий
            'flow_content'      => $comment,  
            'flow_user_id'      => $uid['id'],
            'flow_pubdate'      => date("Y-m-d H:i:s"),
            'flow_url'          => $url_comment,
            'flow_target_id'    => $last_comment_id,
            'flow_about'        => lang('add_comment'),            
            'flow_space_id'     => 0,
            'flow_tl'           => $post['post_tl'], // TL поста
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);        
         
        // Пересчитываем количество комментариев для поста + 1
        PostModel::getNumComments($post_id);
        
        // Оповещение автору ответа, что есть комментарий
        if($answ_id) {
            // Себе не записываем (перенести в общий, т.к. ничего для себя не пишем в notf)
            $answ = AnswerModel::getAnswerOne($answ_id);
            if($uid['id'] != $answ['answer_user_id']) {
                $type = 4; // Ответ на пост        
                NotificationsModel::send($uid['id'], $answ['answer_user_id'], $type, $last_comment_id, $url_comment, 1);
            }
        }
        
        // Уведомление (@login)
        if ($message = Base::parseUser($comment, true, true)) 
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
    
    // Редактируем комментарий
    public function editComment()
    {
        $comm_id    = \Request::getPostInt('comm_id');
        $post_id    = \Request::getPostInt('post_id');
        $comment    = \Request::getPost('comment');

        $post = PostModel::postId($post_id);

        // Получим относительный url поста для возрата
        $url = '/post/' . $post['post_id'] . '/' . $post['post_slug'] . '#comm_' . $comm_id;
        
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
        redirect($url); 
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
    public function userComments()
    {
        $login = \Request::get('login');

        // Если нет такого пользователя 
        $user   = UserModel::getUserLogin($login);
        Base::PageError404($user);
        
        $comm  = CommentModel::userComments($login); 
        
        $result = Array();
        foreach($comm as $ind => $row){
            $row['comment_content'] = Base::text($row['comment_content'], 'line');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Comments-n') . ' ' . $login,
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/comments', 
            'sheet'         => 'user-comments', 
            'meta_title'    => lang('Comments-n') . ' ' . $login .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Comments-n') . ' ' . $login .' '. Config::get(Config::PARAM_HOME_TITLE),
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
}