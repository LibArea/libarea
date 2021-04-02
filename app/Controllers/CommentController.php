<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\PostModel;
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
         
        $comm = CommentModel::getCommentsAll();
 
        $result = Array();
        foreach($comm  as $ind => $row){
            if(!$row['avatar']) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['avatar'] = $row['avatar'];
            $row['content'] = $Parsedown->text($row['comment_content']);
            $row['date'] = Base::ru_date($row['comment_date']);
            $result[$ind] = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'          => 'Все комментарии',
            'title'       => 'Все комментарии' . ' | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Все комментарии на сайте в порядке очередности. ' . $GLOBALS['conf']['sitename'],
        ]; 
 
        return view("comment/all", ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

    // Добавление комментария
    public function create()
    {
        // Получим относительный url поста для возрата (упростить)
        $url = str_replace('//', '', $_SERVER['HTTP_REFERER']);
        $return_url = substr($url, strpos($url, '/') + 1);
        
        $comment = \Request::getPost('comment');
        
        if (Base::getStrlen($comment) < 6 || Base::getStrlen($comment) > 1024)
        {
            Base::addMsg('Длина комментария должна быть от 6 до 1000 знаков', 'error');
            redirect('/' . $url);
            return true;
        }

        $post_id   = \Request::getPostInt('post_id'); // в каком посту ответ
        $comm_id   = \Request::getPostInt('comm_id'); // на какой коммент
        $comment   = $_POST['comment']; // не фильтруем
        $ip        = \Request::getRemoteAddress();      // ip отвечающего 
        
        // id того, кто отвечает
        $account   = \Request::getSession('account');
        $my_id     = $account['user_id'];
        
        // Ограничим частоту добавления
        // CommentModel::getCommentSpeed($my_id);
        
        // Записываем коммент
        $last_id = CommentModel::commentAdd($post_id, $ip, $comm_id, $comment, $my_id);
         
        // Пересчитываем количество комментариев для поста + 1
        PostModel::getNumComments($post_id); // + 1
        
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

        $uid  = Base::getUid();
        $data = [
            'title'             => 'Форма Редактирования',
            'description'       => 'Форма Редактирования...',
            'comm_id'           => $comm_id,
            'post_id'           => $post_id,
            'user_id'           => $user_id,
            'comment_content'   => $comm['comment_content'],
        ]; 
        
        return view("comment/editform", ['data' => $data, 'uid' => $uid]);
    }

    // Покажем форму ответа
    public function addform()
	{
        $comm_id    = \Request::getPostInt('comm_id');
        $post_id    = \Request::getPostInt('post_id');
        $user       = \Request::getSession('account') ?? [];
        
        if(!empty($user['user_id'])) {
             $user_id = $user['user_id'];
        } else {
            $user_id  = 0;
        }
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Форма ответа',
            'description' => 'Форма ответа...',
            'comm_id'     => $comm_id,
            'post_id'     => $post_id,
            'user_id'     => $user_id,
        ]; 
        
        return view("comment/addform", ['data' => $data, 'uid' => $uid]);
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
        
        return view("comment/commuser", ['data' => $data, 'uid' => $uid, 'comments' => $result]);
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
    
}