<?php

namespace App\Controllers;
use Base;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\SpaceModel;
use ImageUpload;
use Parsedown;

class UserController extends \MainController
{
    // Все пользователи
    function index()
    {
        $uid  = Base::getUid();
        $users = UserModel::getUsersAll($uid['id']);
        
        $result = Array();
        foreach($users as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
 
            $row['avatar']        = $row['avatar'];
            $result[$ind]         = $row;
         
        } 
        
        $data = [
            'h1'            => lang('Users'),
            'title'         => lang('Users') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Список всех участников сортированных по дате регистрации сайте ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/user/all', ['data' => $data, 'uid' => $uid, 'users' => $result]);
    }

    // Страница участника
    function profile()
    {
        $login = \Request::get('login');
        $user  = UserModel::getUserLogin($login);

        // Покажем 404
        if(!$user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $post = PostModel::getPostId($user['my_post']);

        if(!$user['avatar'] ) {
            $user['avatar'] = 'noavatar.png';
        } 

        $uid  = Base::getUid();
        $data =[
          'h1'              => $user['login'] . ' - профиль',
          'title'           => $user['login'] . ' - профиль' . ' | ' . $GLOBALS['conf']['sitename'],
          'description'     => 'Страница профиля учасника (посты, комментарии) ' . $user['login'] . ' на ' . $GLOBALS['conf']['sitename'],
          'created_at'      => Base::ru_date($user['created_at']),
          'trust_level'     => UserModel::getUserTrust($user['id']),
          'post_num_user'   => UserModel::getUsersPostsNum($user['id']),
          'comm_num_user'   => UserModel::getUsersCommentsNum($user['id']),
          'space_user'      => SpaceModel::getSpaceId($user['my_space_id']),
        ];

        return view(PR_VIEW_DIR . '/user/profile', ['data' => $data, 'uid' => $uid, 'user' => $user, 'post' => $post]);
    }  

    // Страница настройки профиля
    function settingPage()
    {
        // Данные участника
        $account    = \Request::getSession('account');
        $user       = UserModel::getUserLogin($account['login']);
        
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

        return view(PR_VIEW_DIR . '/user/setting', ['data' => $data, 'uid' => $uid]);
    }
    
    // Изменение профиля
    function settingEdit ()
    {
        $name    = \Request::getPost('name');
        $about   = \Request::getPost('about');
        
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

        // Логин участника
        $account = \Request::getSession('account');
        $login   = $account['login'];
    
        UserModel::editProfile($login, $name, $about);
        
        redirect(PR_VIEW_DIR . '/users/setting');
    }
    
    // Форма загрузки аватарки
    function settingPageAvatar ()
    {
        // Аватар участника
        $account = \Request::getSession('account');
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

        return view(PR_VIEW_DIR . '/user/setting-avatar', ['data' => $data, 'uid' => $uid]);
    }
    
    // Форма изменение пароля
    function settingPageSecurity ()
    {
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Изменение пароля',
            'description' => 'Страница изменение пароля', 
            'password'    => '',
            'password2'   => '',
            'password3'   => '',
        ];

        return view(PR_VIEW_DIR . '/user/setting-security', ['data' => $data, 'uid' => $uid]);
    }
    
    // Изменение аватарки
    function settingAvatarEdit() 
    {
        $account  = \Request::getSession('account');
        $name     = $_FILES['image']['name'];
        $size     = $_FILES['image']['size'];
        $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $width_h  = getimagesize($_FILES['image']['tmp_name']);
       
        $valid =  true;
        if (!in_array($ext, array('jpg','jpeg','png','gif'))) {
            $valid = false;
            Base::addMsg('Тип файла не разрешен', 'error');
            redirect('/users/setting/avatar');
        }

        // Проверка ширины, высоты и размера
        if ($width_h['0'] > 150) {
            $valid = false;
            Base::addMsg('Ширина больше 150 пикселей', 'error');
            redirect('/users/setting/avatar');
        }
        if ($width_h['1'] > 150) {
            $valid = false;
            Base::addMsg('Высота больше 150 пикселей', 'error');
            redirect('/users/setting/avatar');
        }
        if ($size > 50000) {
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
        $password    = \Request::getPost('password');
        $password2   = \Request::getPost('password2');
        $password3   = \Request::getPost('password3');

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
        
        // Данные участника
        $account = \Request::getSession('account');
        $userInfo = UserModel::getUserInfo($account['email']);
       
        if (!password_verify($password, $userInfo['password'])) {
            Base::addMsg('Старый пароль не верен', 'error');
            redirect('/users/setting/security');
        }
        
        $newpass = password_hash($password2, PASSWORD_BCRYPT);
        UserModel::editPassword($account['user_id'], $newpass);

        Base::addMsg('Пароль успешно изменен', 'error');
        redirect('/users/setting');
    }
    
    // Страница закладок участника
    function userFavorite ()
    {
        $uid    = Base::getUid();
        $login  = \Request::get('login');

        $user   = UserModel::getUserLogin($login);

        // Покажем 404
        if(!$user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        // Если страница закладок не участника
        if($user['id'] != $uid['id']){
            redirect('/');
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        
        $fav = UserModel::getUserFavorite($user['id']);
   
        $result = Array();
        foreach($fav as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar']  = 'noavatar.png';
            } 

            $row['avatar']          = $row['avatar'];  
            $row['comment_content'] = $Parsedown->text($row['comment_content']);
            $row['date']            = Base::ru_date($row['post_date']);
            $row['post']            = PostModel::getPostId($row['comment_post_id']);
            $result[$ind]           = $row;
         
        }
        
        $data = [
            'h1'            => 'Избранное ' . $login,
            'title'         => 'Избранное ' . $login . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Избранные посты участника ' . $login . ' в сообществе ' . $GLOBALS['conf']['sitename'],
        ]; 
        
        return view(PR_VIEW_DIR . '/user/favorite', ['data' => $data, 'uid' => $uid, 'favorite' => $result]);   
    }
    
    /////////// СИСТЕМА ИНВАЙТОВ ///////////
    
    // Показ формы инвайта
    public function invitePage()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Invite'),
            'title'         => lang('Invite') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница инвайтов на сайте ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/user/invite', ['data' => $data, 'uid' => $uid]);    
    }
    
    // Отправка запроса инвайта
    public function inviteHandler() 
    {
        $invite = \Request::getPost('invite');
        print_r($invite);
        exit;
    }
    
    // Страница инвайтов пользователя
    function invitationPage() 
    {
        // Данные участника
        $account    = \Request::getSession('account');
        $user       = UserModel::getUserLogin($account['login']);
        
        $result =  UserModel::InvitationResult($user['id']);

        $uid  = Base::getUid();
        $data = [
          'h1'          => 'Инвайты',
          'title'       => 'Мои инвайты',
          'description' => 'Страница личных инвайтов', 
        ];

        return view(PR_VIEW_DIR . '/user/invitation', ['data' => $data, 'uid' => $uid, 'user' => $user,  'result' => $result]);  
    }
    
    // Создать инвайт
    function invitationCreate() {
        
        // Данные участника
        $account    = \Request::getSession('account');
        $user       = UserModel::getUserLogin($account['login']);
        
        $invitation_email = \Request::getPost('email');
        
        if (!$this->prEmail($invitation_email)) {
           Base::addMsg('Недопустимый email', 'error');
           redirect('/users/invitation');
        }
        
        $uInfo = UserModel::getUserInfo($invitation_email);
        if(!empty($uInfo['email'])) {
            
            if ($uInfo['email']) {
                Base::addMsg('Пользователь уже есть на сайте', 'error');
                redirect('/users/invitation');
            }
        } 
        
        $inv_user = UserModel::InvitationOne($user['id']);
 
        if($inv_user['invitation_email'] == $invitation_email) {
            Base::addMsg('Вы уже отсылали приглашение этому пользователю', 'error');
            redirect('/users/invitation');
        }
        
        // + Повторная отправка
        
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = Base::randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();
        
        UserModel::addInvitation($user['id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        Base::addMsg('Инвайт создан', 'error');
        redirect('/users/invitation'); 
    }
    
    // Проверка e-mail
    // Перенести в Base
    private function prEmail($email)
    {
        $pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        return preg_match($pattern, $email);
    } 
}
