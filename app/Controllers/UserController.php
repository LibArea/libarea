<?php

namespace App\Controllers;
use Base;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\PostModel;

use ImageUpload;


class UserController extends \MainController
{
    // Все пользователи
    function index()
    {
        
        $uid  = Base::getUid();
        $data = [
          'title' => 'Все участники',
          'description' => 'Список всех участников сортированных по дате регистрации сайте AreaDev',
          'users' => UserModel::getUsersAll(),
        ];

        return view('/user/all', ['data' => $data, 'uid' => $uid]);
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

        $post = PostModel::getPostProfile($user['my_post']);

        if(!$user['avatar']) {
                $user['avatar'] = 'noavatar.png';
        }
        
        $uid  = Base::getUid();
        $data =[
          'title'         => $user['login'] . ' - профиль',
          'description'   => 'Страница профиля учасника (постов, комментариев) ' . $user['login'],
          'id'            => $user['id'],
          'login'         => $user['login'],
          'name'          => $user['name'],
          'about'         => $user['about'],
          'avatar'        => $user['avatar'],
          'my_post'       => $user['my_post'],
          'post'          => $post,
          'created_at'    => Base::ru_date($user['created_at']),
          'trust_level'   => UserModel::getUserTrust($user['id']),
          'post_num_user' => UserModel::getUsersPostsNum($user['id']),
          'comm_num_user' => UserModel::getUsersCommentsNum($user['id']),
        ];

        return view('/user/profile', ['data' => $data, 'uid' => $uid]);

    }  

    // Страница настройки профиля
    function settingPage()
    {
        
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
      
        $user = UserModel::getUserLogin($account['login']);
        
        if(!$user['avatar']) {
            $user['avatar'] = 'noavatar.png';
        }
        
        $uid  = Base::getUid();
        $data = [
          'title'       => 'Настрока профиля',
          'description' => 'Страница настройки профиля', 
          'login'       => $user['login'],
          'name'        => $user['name'],
          'avatar'      => $user['avatar'],
          'about'       => $user['about'],
          'email'       => $user['email'],
        ];

        return view('/user/setting', ['data' => $data, 'uid' => $uid]);
    }
    
    // Изменение профиля
    function settingEdit ()
    {
        
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }  
        
        $name    = Request::getPost('name');
        $about   = Request::getPost('about');
        
        if (Base::getStrlen($name) < 4 || Base::getStrlen($name) > 20)
        {
          Base::addMsg('Имя должно быть от 3 до ~ 10 символов', 'error');
          redirect('/users/setting');
        }
        
        if (Base::getStrlen($about) > 350)
        {
          Base::addMsg('О себе должно быть меньше символов', 'error');
          redirect('/users/setting');
        }

        $login   = $account['login'];
    
        UserModel::editProfile($login, $name, $about);
        
        redirect('/users/setting');
    }
    
    // Форма загрзуки аватарки
    function settingPageAvatar ()
    {
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
        
        $ava     = UserModel::getAvatar($account['login']);
        $avatar  = $ava['avatar'];
        
        if(!$avatar) {
            $avatar = 'noavatar.png';
        } 
        
        
        $uid  = Base::getUid();
        $data = [
            'title'  => 'Изменение аватарки',
            'description' => 'Страница изменение аватарки', 
            'avatar' => $avatar,
        ];

        return view('/user/setting-avatar', ['data' => $data, 'uid' => $uid]);
 
    }
    
    // Форма изменение пароля
    function settingPageSecurity ()
    {
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Изменение пароля',
            'description' => 'Страница изменение пароля', 
            'password'    => '',
            'password2'   => '',
            'password3'   => '',
        ];

        return view('/user/setting-security', ['data' => $data, 'uid' => $uid]);
 
    }
    
    // Изменение аватарки
    function settingAvatarEdit() 
    {
        
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
  
        $name     = $_FILES['image']['name'];
        $size     = $_FILES['image']['size'];
        $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
       
        $valid =  true;
        if (!in_array($ext, array('jpg','jpeg','png','gif'))) {
            $valid = false;
            Base::addMsg('Тип файла не разрешен', 'error');
            redirect('/users/setting/avatar');
        }

        if ($size/1024/1024 > 10) {
            $valid = false;
            Base::addMsg('Размер файла превышает допустимый', 'error');
            redirect('/users/setting/avatar');
        }

        if ($valid) {
            
            // 110px и 16px
            $path_img       = HLEB_PUBLIC_DIR. '/uploads/avatar/';
            $path_img_small = HLEB_PUBLIC_DIR. '/uploads/avatar/small/';
            
            $image = new ImageUpload('image'); 
            
            $image->resize(110, 110, 'crop');            
            $img = $image->saveTo($path_img, $account['user_id']);
            
            $image->resize(16, 16);            
            $image->saveTo($path_img_small, $account['user_id']);
            
            // Получим страую если оно есть, удаляем
            $avatar = UserModel::getAvatar($account['login']);
            
            // Удаляем старые аватарки
            chmod($path_img . $avatar['avatar'], 0777);
            chmod($path_img_small . $avatar['avatar'], 0777);
            unlink($path_img . $avatar['avatar']);
            unlink($path_img_small . $avatar['avatar']);
    
            // Запишем новую 
            UserModel::setAvatar($account['login'], $img);
            
            Base::addMsg('Аватарка изменена', 'error');
            redirect('/users/setting/avatar');
            
        }
    }
    

    // Изменение пароля
    function settingSecurityEdit()
    {
        
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }  
        
        $password    = Request::getPost('password');
        $password2   = Request::getPost('password2');
        $password3   = Request::getPost('password3');

        if ($password2 != $password3) {
            Base::addMsg('Пароли не совпадают', 'error');
            redirect('/users/setting/security');
        }
        
        if (substr_count($password2, ' ') > 0) {
            Base::addMsg('Пароль не может содержать пробелов', 'error');
            redirect('/users/setting/security');
        }

        if (Base::getStrlen($password2) < 8 || Base::getStrlen($password2) > 24) {
            Base::addMsg('Длина пароля должна быть от 8 до 24 знаков', 'error');
            redirect('/users/setting/security');
        }
        
        $userInfo = UserModel::getUserInfo($account['email']);
       
        if (!password_verify($password, $userInfo['password'])) {
            Base::addMsg('Старый пароль не верен', 'error');
            redirect('/users/setting/security');
        }
        
        $newpass = password_hash($password2, PASSWORD_BCRYPT);
        UserModel::editPassword($account['login'], $newpass);

        Base::addMsg('Пароль успешно изменен', 'error');
        redirect('/users/setting');
        
    }
    function userFavorite ()
    {
        $login = Request::get('login');

        $user  = UserModel::getUserLogin($login);

        // Покажем 404
        if(!$user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        $uid  = Base::getUid();
        $data = [
            'title'         => 'Избранное ' . $login,
            'description'   => 'Избранные посты участника ' . $login,
            'favorite'      => 0,
        ]; 
        
        return view("user/favorite", ['data' => $data, 'uid' => $uid]);   
    }
}
