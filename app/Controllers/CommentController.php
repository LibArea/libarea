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
    

        $data = [
            'comments' => $result,
            'title' => 'Комментарии',
            'msg'      => Base::getMsg(),
        ]; 
 
        return view("comment/all", ['data' => $data]);
        
        
    }


    // Добавление комментария
    public function create()
    {
        // Авторизировались или нет
        if (!Request::getSession('account'))
        {
            return false;
        }  
        
        // получим относительный url поста для возрата (упростить)
        $url = str_replace('//', '', $_SERVER['HTTP_REFERER']);
        $return_url = substr($url, strpos($url, '/') + 1);
        
        $comment = Request::getPost('comment');
        
        if (strlen($comment) < 6 || strlen($comment) > 1024)
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
        $account = Request::getSession('account');
        $my_id = $account['user_id'];
        
        // записываем покммент
        CommentModel::commentAdd($post_id, $ip, $comm_id, $comment, $my_id);
        
        // Пересчитываем количество комментариев для поста + 1
        PostModel::getNumComments($post_id); // + 1
        
        redirect('/' . $return_url); 

    }

   // Покажем форму ответа
    public function addform()
	{
     
        $id = (int)Request::getPost('comm_id');
        $post_id = (int)Request::getPost('post_id');
         
        // id того, кто госует за комментарий
        $user = Request::getSession('account') ?? [];
        
        if(!empty($user['user_id'])) {
             $user_id = $user['user_id'];
        } else {
            $user_id = 0;
        }
        
     
        $data = [
            'comm_id' => $id,
            'post_id' => $post_id,
            'user_id' => $user_id,
            'msg'     => Base::getMsg(),
        ]; 
        
        return view("comment/addform", ['data' => $data]);
    }


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
        
 
        $data = [
            'comments' => $result,
            'title' => 'Комментарии ' . $login,
            'msg'      => Base::getMsg(),
        ]; 
        
        return view("comment/commuser", ['data' => $data]);
         
    }

    //  comment_del 0/1 
    public function delete($id)
    {

    }
}