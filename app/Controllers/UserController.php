<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\SpaceModel;
use ImageUpload;
use Parsedown;
use Base;

class UserController extends \MainController
{
    // Все пользователи
    function index()
    {
        $uid    = Base::getUid();
        $users  = UserModel::getUsersAll($uid['id']);
        
        $data = [
            'h1'            => lang('Users'),
            'title'         => lang('Users') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('desc-user-all') . ' ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/user/all', ['data' => $data, 'uid' => $uid, 'users' => $users]);
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

        $uid  = Base::getUid();
        $data =[
          'h1'              => $user['login'] . ' - профиль',
          'title'           => $user['login'] . ' - профиль' . ' | ' . $GLOBALS['conf']['sitename'],
          'description'     => lang('desc-profile') . ' ' . $user['login'] . ' - ' . $GLOBALS['conf']['sitename'],
          'created_at'      => Base::ru_date($user['created_at']),
          'trust_level'     => UserModel::getUserTrust($user['id']),
          'post_num_user'   => UserModel::getUsersPostsNum($user['id']),
          'comm_num_user'   => UserModel::getUsersCommentsNum($user['id']), 
          'space_user'      => SpaceModel::getSpaceUserId($user['id']),
        ];

        return view(PR_VIEW_DIR . '/user/profile', ['data' => $data, 'uid' => $uid, 'user' => $user, 'post' => $post]);
    }  

    // Страница настройки профиля
    function settingPage()
    {
        // Данные участника
        $login  = \Request::get('login');
        $uid    = Base::getUid();
        $user   = UserModel::getUserLogin($uid['login']);
   
        // Ошибочный Slug в Url
        if($login != $user['login']) {
            redirect('/u/' . $user['login'] . '/setting');
        }
        
        $data = [
          'h1'          => lang('Setting profile'),
          'title'       => lang('Setting profile'),
          'description' => lang('Setting profile page'), 
        ];

        return view(PR_VIEW_DIR . '/user/setting', ['data' => $data, 'uid' => $uid, 'user' => $user]);
    }
    
    // Изменение профиля
    function settingEdit ()
    {
        $uid    = Base::getUid();
        $name   = \Request::getPost('name');
        $about  = \Request::getPost('about');
        
        if (Base::getStrlen($name) < 4 || Base::getStrlen($name) > 11) {
          Base::addMsg(lang('name-info-err'), 'error');
          redirect('/u/' . $uid['login'] . '/setting');
        }
   
        if (Base::getStrlen($about) < 4 || Base::getStrlen($about) > 320) {
          Base::addMsg(lang('about-info-err'), 'error');
          redirect('/u/' . $uid['login'] . '/setting');
        }  

        UserModel::editProfile($uid['login'], $name, $about);
        
        Base::addMsg(lang('Changes saved'), 'success');
        redirect('/u/' . $uid['login'] . '/setting');
    }
    
    // Форма загрузки аватарки
    function settingPageAvatar ()
    {
        $uid  = Base::getUid();
        $login  = \Request::get('login');

        // Ошибочный Slug в Url
        if($login != $uid['login']) {
            redirect('/u/' . $uid['login'] . '/setting/avatar');
        }

        $data = [
            'h1'            => lang('Change avatar'),
            'title'         => lang('Change avatar'),
            'description'   => lang('Change avatar page'), 
        ];

        return view(PR_VIEW_DIR . '/user/setting-avatar', ['data' => $data, 'uid' => $uid]);
    }
    
    // Форма изменение пароля
    function settingPageSecurity ()
    {
        $uid  = Base::getUid();
        $login  = \Request::get('login');

        // Ошибочный Slug в Url
        if($login != $uid['login']) {
            redirect('/u/' . $uid['login'] . '/setting/security');
        }
        
        $data = [
            'h1'            => lang('Change password'),
            'title'         => lang('Change password'),
            'description'   => lang('Change password page'), 
            'password'      => '',
            'password2'     => '',
            'password3'     => '',
        ];

        return view(PR_VIEW_DIR . '/user/setting-security', ['data' => $data, 'uid' => $uid]);
    }
    
    // Изменение аватарки
    function settingAvatarEdit() 
    {
        $uid  = Base::getUid();
        
        $name     = $_FILES['image']['name'];
        $size     = $_FILES['image']['size'];
        $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $width_h  = getimagesize($_FILES['image']['tmp_name']);
       
        $valid =  true;
        if (!in_array($ext, array('jpg','jpeg','png','gif'))) {
            $valid = false;
            Base::addMsg(lang('file-type-not-err'), 'error');
            redirect('/u/' . $uid['login'] . '/setting/avatar');
        }

        // Проверка ширины, высоты и размера
        if ($width_h['0'] > 150) {
            $valid = false;
            Base::addMsg('Ширина больше 150 пикселей', 'error');
            redirect('/u/' . $uid['login'] . '/setting/avatar');
        }
        if ($width_h['1'] > 150) {
            $valid = false;
            Base::addMsg('Высота больше 150 пикселей', 'error');
            redirect('/u/' . $uid['login'] . '/setting/avatar');
        }
        if ($size > 50000) {
            $valid = false;
            Base::addMsg('Размер файла превышает допустимый', 'error');
            redirect('/u/' . $uid['login'] . '/setting/avatar');
        }

        if ($valid) {
 
            // 110px и 16px
            $path_img       = HLEB_PUBLIC_DIR. '/uploads/avatar/';
            $path_img_small = HLEB_PUBLIC_DIR. '/uploads/avatar/small/';
            
            $image = new ImageUpload('image'); 
            
            $image->resize(110, 110, 'crop');            
            $img = $image->saveTo($path_img, $uid['id']);
            
            $image->resize(16, 16);            
            $image->saveTo($path_img_small, $uid['id']);
            
            $avatar = UserModel::getAvatar($uid['login']);
            
            // Удалим старую аватарку, кроме дефолтной
            if($avatar['avatar'] != 'noavatar.png') {
                chmod($path_img . $avatar['avatar'], 0777);
                chmod($path_img_small . $avatar['avatar'], 0777);
                unlink($path_img . $avatar['avatar']);
                unlink($path_img_small . $avatar['avatar']);
            }            
            
            // Запишем новую 
            UserModel::setAvatar($uid['login'], $img);
            
            Base::addMsg(lang('Avatar changed'), 'error');
            redirect('/u/' . $uid['login'] . '/setting/avatar');
        }
    }
    
    // Изменение пароля
    function settingSecurityEdit()
    {
        $uid  = Base::getUid();
        $password    = \Request::getPost('password');
        $password2   = \Request::getPost('password2');
        $password3   = \Request::getPost('password3');

        if ($password2 != $password3) {
            Base::addMsg(lang('pass-match-err'), 'error');
            redirect('/u/' . $uid['login'] . '/setting/security');
        }
        
        if (substr_count($password2, ' ') > 0) {
            Base::addMsg(lang('pass-gap-err'), 'error');
            redirect('/u/' . $uid['login'] . '/setting/security');
        }

        if (Base::getStrlen($password2) < 8 || Base::getStrlen($password2) > 24) {
            Base::addMsg(lang('pass-length-err'), 'error');
            redirect('/u/' . $uid['login'] . '/setting/security');
        }
        
        // Данные участника
        $account = \Request::getSession('account');
        $userInfo = UserModel::getUserInfo($account['email']);
       
        if (!password_verify($password, $userInfo['password'])) {
            Base::addMsg(lang('old-password-err'), 'error');
            redirect('/u/' . $uid['login'] . '/setting/security');
        }
        
        $newpass = password_hash($password2, PASSWORD_BCRYPT);
        UserModel::editPassword($account['user_id'], $newpass);

        Base::addMsg(lang('Password changed'), 'error');
        redirect('/u/' . $uid['login'] . '/setting');
    }
    
    // Страница закладок участника
    function userFavorite ()
    {
        $login  = \Request::get('login');
        
        $uid    = Base::getUid();
        $user   = UserModel::getUserLogin($uid['login']);

        // Ошибочный Slug в Url
        if($login != $uid['login']){
            redirect('/u/' . $user['login'] . '/favorite');
        }

        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        $fav = UserModel::getUserFavorite($user['id']);
   
        $result = Array();
        foreach($fav as $ind => $row){
             
            $row['post_date']       = (empty($row['post_date'])) ? $row['post_date'] : Base::ru_date($row['post_date']);
            $row['answer_content'] = $Parsedown->text($row['answer_content']);
            $row['date']            = $row['post_date'];
            $row['post']            = PostModel::getPostId($row['answer_post_id']);
            $result[$ind]           = $row;
        }
        
        $data = [
            'h1'            => lang('Favorites') . ' ' . $login,
            'title'         => lang('Favorites') . ' ' . $login . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('Favorites') . ' ' . $login . ' — ' . $GLOBALS['conf']['sitename'],
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
            'description'   => lang('Invite') . ' — ' . $GLOBALS['conf']['sitename'],
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
        // Страница участника и данные
        $login      = \Request::get('login');
       
        // Кто смотрит
        $uid    = Base::getUid();
        $user   = UserModel::getUserId($uid['id']);
        
        // Запретим смотреть инвайты чужого профиля
        if($login != $user['login']) {
            redirect('/u/' . $user['login'] . '/invitation');
        }

        // Покажем 404
        if(!$user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $Invitation = UserModel::InvitationResult($uid['id']);
 
        $data = [
          'h1'          => lang('Invites'),
          'title'       => lang('Invites') . ' | ' . $GLOBALS['conf']['sitename'],
          'description' => lang('Invites') . ' - ' . $GLOBALS['conf']['sitename'], 
        ];

        return view(PR_VIEW_DIR . '/user/invitation', ['data' => $data, 'uid' => $uid, 'user' => $user,  'result' => $Invitation]);  
    }
    
    // Создать инвайт
    function invitationCreate() 
    {
        // Данные участника
        $uid    = Base::getUid();
        
        $invitation_email = \Request::getPost('email');
        
        if(!filter_var($invitation_email, FILTER_VALIDATE_EMAIL)) {
           Base::addMsg(lang('Invalid') . ' email', 'error');
           redirect('/u/' . $uid['login'] . '/invitation');
        }
        
        $uInfo = UserModel::getUserInfo($invitation_email);
        if(!empty($uInfo['email'])) {
            
            if ($uInfo['email']) {
                Base::addMsg(lang('user-already'), 'error');
                redirect('/u/' . $uid['login'] . '/invitation');
            }
        } 
        
        $inv_user = UserModel::InvitationOne($uid['id']);
 
        if($inv_user['invitation_email'] == $invitation_email) {
            Base::addMsg(lang('invate-to-replay'), 'error');
            redirect('/u/' . $uid['login'] . '/invitation');
        }
        
        // + Повторная отправка
        
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = Base::randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();
        
        UserModel::addInvitation($uid['id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        Base::addMsg(lang('Invite created'), 'success');
        redirect('/u/' . $uid['login'] . '/invitation'); 
    }
    
}
