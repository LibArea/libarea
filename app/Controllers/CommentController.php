<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\PostModel;
use App\Models\VotesCommentModel;
use App\Models\NotificationsModel;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use Base;
use Parsedown;

class CommentController extends \MainController
{
    // Все комментарии
    public function index()
    {
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
         
        $pg = \Request::getInt('page'); 
        $page = (!$pg) ? 1 : $pg;
        
        $account    = \Request::getSession('account');
        $user_id    = $account ? $account['user_id'] : 0;
         
        $pagesCount = CommentModel::getCommentAllCount();  
        $comm       = CommentModel::getCommentsAll($page, $user_id);
 
        $result = Array();
        foreach($comm  as $ind => $row){
            if(!$row['avatar']) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['avatar']  = $row['avatar'];
            $row['content'] = $Parsedown->text($row['comment_content']);
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
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('All comments'),
            'title'         => lang('All comments') . ' | ' . $GLOBALS['conf']['sitename'] . $num,
            'description'   => lang('comments-desc') .' '. $GLOBALS['conf']['sitename'] . $num,
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
        ]; 
 
        return view(PR_VIEW_DIR . '/comment/all', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

    // Добавление комментария
    public function create()
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
        $comm_id   = \Request::getPostInt('comm_id');   // на какой коммент
        $comment   = $_POST['comment'];                 // не фильтруем
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
        $last_id = CommentModel::commentAdd($post_id, $ip, $comm_id, $comment, $my_id);
         
        // Адрес комментария 
        $url = $return_url . '#comm_' . $last_id; 
         
        // Добавим в чат и поток
        $data_flow = [
            'flow_action_id'    => 2, // add комментарий
            'flow_content'      => $comment, // не фильтруем
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
        
        // Оповещение автору комментария если это ответ на комментарий
        if($comm_id) {
            // Себе не записываем (перенести в общий, т.к. ничего для себя не пишем в notf)
            $infcomm = CommentModel::getCommentsOne($comm_id);
            if($my_id != $infcomm['comment_user_id']) {
                $type = 2; // Ответ на комментарий        
                NotificationsModel::send($my_id, $infcomm['comment_user_id'], $type, $last_id, $url, 1);
            }
        }
        
        redirect('/' . $return_url . '#comm_' . $last_id); 
    }
    
    // Редактируем комментарий
    public function editComment()
    {
        $comm_id    = \Request::getPostInt('comm_id');
        $post_id    = \Request::getPostInt('post_id');
        $comment    = $_POST['comment']; // не фильтруем
        
        // Получим относительный url поста для возрата (упростить)
        $url = str_replace('//', '', $_SERVER['HTTP_REFERER']);
        $return_url = substr($url, strpos($url, '/') + 1);
        
        // id того, кто редактирует
        $account   = \Request::getSession('account');
        $user_id   = $account['user_id'];
        
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
	public function editform()
	{
        $comm_id    = \Request::getPostInt('comm_id');
        $post_id    = \Request::getPostInt('post_id');
         
        // id того, кто редактирует
        $account   = \Request::getSession('account');
        $user_id   = $account['user_id'];
        
        $comm = CommentModel::getCommentsOne($comm_id);

        // Проверим автора комментария и админа
        if($user_id != $comm['comment_user_id'] && $account['trust_level'] != 5) {
            return true; 
        }

        $data = [
            'comm_id'           => $comm_id,
            'post_id'           => $post_id,
            'user_id'           => $user_id,
            'comment_content'   => $comm['comment_content'],
        ]; 
        
        return view(PR_VIEW_DIR . '/comment/edit-form', ['data' => $data]);
    }

	// Покажем форму ответа
	public function addform()
	{
        $comm_id    = \Request::getPostInt('comm_id');
        $post_id    = \Request::getPostInt('post_id');
        
        $uid  = Base::getUid();
        $data = [
            'comm_id'     => $comm_id,
            'post_id'     => $post_id,
        ]; 
        
        return view(PR_VIEW_DIR . '/comment/add-form', ['data' => $data, 'uid' => $uid]);
    }

    // Комментарии участника
    public function userComments()
    {
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        $login = \Request::get('login');
       
        $comm  = CommentModel::getUsersComments($login); 

        // Покажем 404
        if(!$comm) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        $result = Array();
        foreach($comm as $ind => $row){

            if(!$row['avatar'] ) {
                $row['avatar']  = 'noavatar.png';
            } 

            $row['avatar']  = $row['avatar'];
            $row['content'] = $Parsedown->text($row['comment_content']);
            $row['date']    = Base::ru_date($row['comment_date']);
         
            $result[$ind]   = $row;
         
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'          => 'Комментарии ' . $login,
            'title'       => 'Комментарии ' . $login . ' | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Страница комментариев учасника ' . $login . ' на сайте ' . $GLOBALS['conf']['sitename'],
        ]; 
        
        return view(PR_VIEW_DIR . '/comment/comm-user', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

    // Удаление комментария
    public function deletComment()
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $comm_id = \Request::getPostInt('comm_id');
        
        CommentModel::CommentsDel($comm_id);
        
        return false;
    }
    
    // Помещаем комментарий в закладки
    public function addCommentFavorite()
    {
        $comm_id = \Request::getPostInt('comm_id');
        $comm    = CommentModel::getCommentsOne($comm_id); 
        
        if(!$comm) {
            redirect('/');
        }
        
        CommentModel::setCommentFavorite($comm_id, $_SESSION['account']['user_id']);
       
        return true;
    } 
}