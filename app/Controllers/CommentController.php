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
         
        $comm =  CommentModel::getCommentsAll();
 
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
        
        // получим относительный url поста для возрата (упростить)
        $url = str_replace('//', '', $_SERVER['HTTP_REFERER']);
        $return_url = substr($url, strpos($url, '/') + 1);
        
        $comment = Request::getPost('comment');
        
        if (Base::getStrlen($comment) < 6 || Base::getStrlen($comment) > 1024)
        {
            Base::addMsg('Длина комментария должна быть от 6 до 1000 знаков', 'error');
            redirect('/' . $vurl);
            return true;
        }

        $post_id   = (int)Request::getPost('post_id'); // в каком посту ответ
        $comm_id   = (int)Request::getPost('comm_id'); // на какой коммент
        $comment   = $_POST['comment']; // не фильтруем
        $ip        = Request::getRemoteAddress();      // ip отвечающего 
        
        // id того, кто отвечает
        $account   = Request::getSession('account');
        $my_id     = $account['user_id'];
        
        // Ограничим частоту добавления
        // CommentModel::getCommentSpeed($my_id);
        
        // Записываем покммент
        $last_id = CommentModel::commentAdd($post_id, $ip, $comm_id, $comment, $my_id);
         
        // Пересчитываем количество комментариев для поста + 1
        PostModel::getNumComments($post_id); // + 1
        
        redirect('/' . $return_url . '#comm_' . $last_id); 

    }

    // Покажем форму ответа
    public function addform()
	{
     
        $id = (int)Request::getPost('comm_id');
        $post_id = (int)Request::getPost('post_id');
         
        $user = Request::getSession('account') ?? [];
        
        if(!empty($user['user_id'])) {
             $user_id = $user['user_id'];
        } else {
            $user_id  = 0;
        }
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Форма ответа',
            'description' => 'Форма ответа...',
            'comm_id'     => $id,
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
        
        $login = Request::get('login');
       
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
        $account = Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $id = Request::getPost('comm_id');
        
        CommentModel::CommentsDel($id);
        
        return false;
    }
    
}