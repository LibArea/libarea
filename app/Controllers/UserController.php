<?php

namespace App\Controllers;
use Base;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;

class UserController extends \MainController
{
    // Все пользователи
    function index()
    {
     
        $data = [
          'title' => 'Все участники',
          'users' => UserModel::getUsersAll(),
          'msg'   => Base::getMsg(),
        ];

        return view('/user/all', ['data' => $data]);
    }

    // Страница участника
    function profile()
    {

        $login = Request::get('login');
        $user  = UserModel::getUserLogin($login);

        // Покажем 404
        if(!$user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        if(!$user['avatar']) {
                $user['avatar'] = 'noavatar.png';
        }

        $data =[
          'title'         => $user['login'] . ' - профиль',
          'id'            => $user['id'],
          'login'         => $user['login'],
          'name'          => $user['name'],
          'about'         => $user['about'],
          'avatar'        => $user['avatar'],
          'created_at'    => Base::ru_date($user['created_at']),
          'post_num_user' => UserModel::getUsersPostsNum($user['id']),
          'comm_num_user' => UserModel::getUsersCommentsNum($user['id']),
          'msg'           => Base::getMsg(),
        ];

        return view('/user/profile', ['data' => $data]);

    }  

    // Страница настройки профиля
    function settingPage()
    {
        
        if(!Request::getSession('account')) {
            redirect('/');
        }
      
        $account = Request::getSession('account'); 
        $user = UserModel::getUserLogin($account['login']);
        
        if(!$user['avatar']) {
            $user['avatar'] = 'noavatar.png';
        }
        
        $data = [
          'title'  => 'Настрока профиля',
          'login'  => $user['login'],
          'name'   => $user['name'],
          'avatar' => $user['avatar'],
          'about'  => $user['about'],
          'email'  => $user['email'],
          'msg'    => Base::getMsg(),
        ];

        return view('/user/setting', ['data' => $data]);
    }
    
    // Изменение профиля
    function settingEdit ()
    {
        
        if(!Request::getSession('account')) {
            redirect('/');
        }  
        
        $name    = Request::getPost('name');
        $about   = Request::getPost('about');
        
        if (strlen($name) < 4 || strlen($name) > 20)
        {
          Base::addMsg('Имя должно быть от 3 до ~ 10 символов', 'error');
          redirect('/users/setting');
        }
        
        if (strlen($about) > 450)
        {
          Base::addMsg('О себе должно быть меньше символов', 'error');
          redirect('/users/setting');
        }
   
        $account = Request::getSession('account'); 
        $login   = $account['login'];
       
        UserModel::editProfile($login, $name, $about);
        
        redirect('/users/setting');
    }
    
}
