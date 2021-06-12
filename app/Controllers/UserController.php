<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\SpaceModel;
use Lori\Config;
use Lori\Base;
use SimpleImage;

class UserController extends \MainController
{
    // Все пользователи
    function index()
    {
        $uid    = Base::getUid();
        $users  = UserModel::getUsersAll($uid['id']);
        
        $data = [
            'h1'            => lang('Users'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/users',
            'sheet'         => 'users', 
            'meta_title'    => lang('Users') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('desc-user-all') .' '. Config::get(Config::PARAM_HOME_TITLE),   
        ];

        Request::getHead()->addStyles('/assets/css/users.css'); 
        
        return view(PR_VIEW_DIR . '/user/all', ['data' => $data, 'uid' => $uid, 'users' => $users]);
    }

    // Страница участника
    function profile()
    {
        $login = \Request::get('login');
        $user  = UserModel::getUserLogin($login);

        // Покажем 404
        Base::PageError404($user);

        $post = PostModel::postId($user['my_post']);
        
        if(!$user['about']) { 
            $user['about'] = lang('Riddle') . '...';
        } 

        $site_name = Config::get(Config::PARAM_NAME);
        $meta_title = sprintf(lang('title-profile'), $user['login'], $user['name'], $site_name); 
        $meta_desc  = sprintf(lang('desc-profile'), $user['login'], $user['about'], $site_name);

        \Request::getHead()->addStyles('/assets/css/users.css');

        if($user['is_deleted'] == 1) {
            \Request::getHead()->addMeta('robots', 'noindex');
        }
        
        // Просмотры профиля
        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = array();
        }

        if (!isset($_SESSION['usernumbers'][$user['id']])) {
            UserModel::userHits($user['id']); 
            $_SESSION['usernumbers'][$user['id']] = $user['id'];
        }

        $uid  = Base::getUid();
        $data =[
            'h1'            => $user['login'],
            'created_at'    => lang_date($user['created_at']),
            'trust_level'   => UserModel::getUserTrust($user['id']),
            'post_num_user' => UserModel::userPostsNum($user['id']),
            'answ_num_user' => UserModel::userAnswersNum($user['id']),
            'comm_num_user' => UserModel::userCommentsNum($user['id']), 
            'space_user'    => SpaceModel::getSpaceUserId($user['id']),
            'badges'        => UserModel::getBadgeUserAll($user['id']),
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $user['login'],
            'sheet'         => 'profile',
            'img'           => Config::get(Config::PARAM_URL) . '/uploads/users/avatars/' . $user['avatar'],
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
        ];

        return view(PR_VIEW_DIR . '/user/profile', ['data' => $data, 'uid' => $uid, 'user' => $user, 'onepost' => $post]);
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
            'h1'            => lang('Setting profile'),
            'canonical'     => '***',
            'sheet'         => 'setting', 
            'meta_title'    => lang('Setting profile'),
            'meta_desc'     => '***',
        ];

        return view(PR_VIEW_DIR . '/user/setting', ['data' => $data, 'uid' => $uid, 'user' => $user]);
    }
    
    // Изменение профиля
    function settingEdit ()
    {
        $name           = \Request::getPost('name');
        $about          = \Request::getPost('about');
        $color          = \Request::getPost('color');
        $website        = \Request::getPost('website');
        $location       = \Request::getPost('location');
        $public_email   = \Request::getPost('public_email');
        $skype          = \Request::getPost('skype');
        $twitter        = \Request::getPost('twitter');
        $telegram       = \Request::getPost('telegram');
        $vk             = \Request::getPost('vk');
        
        if(!$color) {
           $color  = '#339900'; 
        }

        // См. https://github.com/Respect/Validation
        // https://github.com/jquery-validation/jquery-validation
        // https://github.com/mikeerickson/validatorjs
        
        $uid        = Base::getUid();
        $redirect   = '/u/' . $uid['login'] . '/setting';
        Base::Limits($name, lang('Name'), '4', '11', $redirect);

        if(!filter_var($public_email, FILTER_VALIDATE_EMAIL)) {
            $public_email = '';
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $skype)) {
            $skype = '';
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $twitter)) {
            $skype = '';
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $telegram)) {
            $skype = '';
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $vk)) {
            $vk = '';
        }
        
        $about          = empty($about) ? '' : $about;
        $website        = empty($website) ? '' : $website;
        $location       = empty($location) ? '' : $location;
        $public_email   = empty($public_email) ? '' : $public_email;
        $skype          = empty($skype) ? '' : $skype;
        $twitter        = empty($twitter) ? '' : $twitter;
        $telegram       = empty($telegram) ? '' : $telegram;
        $vk             = empty($vk) ? '' : $vk; 

        UserModel::editProfile($uid['login'], $name, $color, $about, $website, $location, $public_email, $skype,$twitter, $telegram, $vk);
        
        Base::addMsg(lang('Changes saved'), 'success');
        redirect($redirect);
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

        $userInfo           = UserModel::getUserLogin($uid['login']);
        
        $data = [
            'h1'            => lang('Change avatar'),
            'canonical'     => '/***', 
            'sheet'         => 'setting-ava', 
            'meta_title'    => lang('Change avatar'),
            'meta_desc'     => lang('Change avatar page'),
        ];

        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        return view(PR_VIEW_DIR . '/user/setting-avatar', ['data' => $data, 'uid' => $uid, 'user' => $userInfo]);
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
            'password'      => '',
            'password2'     => '',
            'password3'     => '',
            'canonical'     => '/***', 
            'sheet'         => 'setting-pass', 
            'meta_title'    => lang('Change password'),
            'meta_desc'     => lang('Change password'),
        ];

        return view(PR_VIEW_DIR . '/user/setting-security', ['data' => $data, 'uid' => $uid]);
    }
    
    // Изменение аватарки
    function settingAvatarEdit() 
    {
        $uid        = Base::getUid();
        $redirect   = '/u/' . $uid['login'] . '/setting/avatar';
        
        // Аватар
        $name = $_FILES['images']['name'][0];
        
        if($name) {
            // 160px и 18px
            $path_img       = HLEB_PUBLIC_DIR. '/uploads/users/avatars/';
            $path_img_small = HLEB_PUBLIC_DIR. '/uploads/users/avatars/small/';
            $filename =  'a-' . $uid['id'] . '-' . time();
            $file = $_FILES['images']['tmp_name'][0];
            
            $image = new  SimpleImage();
            
            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(160, 160)
                ->toFile($path_img . $filename .'.jpeg', 'image/jpeg')
                ->resize(18, 18)
                ->toFile($path_img_small . $filename .'.jpeg', 'image/jpeg');
                    
            $new_ava    = $filename . '.jpeg';
            $avatar     = UserModel::getAvatar($uid['login']);
     
            // Удалим старую аватарку, кроме дефолтной
            if($avatar['avatar'] != 'noavatar.png' && $avatar['avatar'] != $new_ava) {
                chmod($path_img . $avatar['avatar'], 0777);
                chmod($path_img_small . $avatar['avatar'], 0777);
                unlink($path_img . $avatar['avatar']);
                unlink($path_img_small . $avatar['avatar']);
            }            
            
            // Запишем новую 
            UserModel::setAvatar($uid['login'], $new_ava);
            Base::addMsg(lang('Avatar changed'), 'success');
        }
        
        // Обложка
        $covername  = $_FILES['cover']['name'][0];
        if($covername) {
                 
            // 1920px / 350px
            $path_cover_img       = HLEB_PUBLIC_DIR. '/uploads/users/cover/';
            $filename =  'cover-' . $uid['id'] . '-' . time();
            $file_cover = $_FILES['cover']['tmp_name'][0];
            
            $image = new  SimpleImage();
            
            $image
                ->fromFile($file_cover)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(1920, 350)
                ->toFile($path_cover_img . $filename .'.jpeg', 'image/jpeg');
                    
            $new_cover  = $filename . '.jpeg';
            $cover      = UserModel::getCover($uid['login']);
     
            // Удалим старую аватарку, кроме дефолтной
            if($cover['cover_art'] != 'cover_art.jpeg' && $cover['cover_art'] != $new_cover) {
                chmod($path_cover_img . $cover['cover_art'], 0777);
                unlink($path_cover_img . $cover['cover_art']);
            }            
            
            // Запишем обложку 
            UserModel::setCover($uid['login'], $new_cover);
            Base::addMsg(lang('Cover changed'), 'success');
        }
         
        redirect($redirect);
    }
    
    // Изменение пароля
    function settingSecurityEdit()
    {
        $uid  = Base::getUid();
        $password    = \Request::getPost('password');
        $password2   = \Request::getPost('password2');
        $password3   = \Request::getPost('password3');

        $redirect = '/u/' . $uid['login'] . '/setting/security';
        if ($password2 != $password3) {
            Base::addMsg(lang('pass-match-err'), 'error');
            redirect($redirect);
        }
        
        if (substr_count($password2, ' ') > 0) {
            Base::addMsg(lang('pass-gap-err'), 'error');
            redirect($redirect);
        }

        if (Base::getStrlen($password2) < 8 || Base::getStrlen($password2) > 32) {
            Base::addMsg(lang('pass-length-err'), 'error');
            redirect($redirect);
        }
        
        // Данные участника
        $account = \Request::getSession('account');
        $userInfo = UserModel::userInfo($account['email']);
       
        if (!password_verify($password, $userInfo['password'])) {
            Base::addMsg(lang('old-password-err'), 'error');
            redirect($redirect);
        }
        
        $newpass = password_hash($password2, PASSWORD_BCRYPT);
        UserModel::editPassword($account['user_id'], $newpass);

        Base::addMsg(lang('Password changed'), 'success');
        redirect($redirect);
    }
    
    // Страница закладок участника
    function userFavorites ()
    {
        $login  = \Request::get('login');
        
        $uid    = Base::getUid();
        $user   = UserModel::getUserLogin($uid['login']);

        // Ошибочный Slug в Url
        if($login != $uid['login']){
            redirect('/u/' . $user['login'] . '/favorite');
        }
  
        $fav = UserModel::userFavorite($user['id']);
   
        $result = Array();
        foreach($fav as $ind => $row){
            $row['post_date']       = (empty($row['post_date'])) ? $row['post_date'] : lang_date($row['post_date']);
            $row['answer_content']  = Base::text($row['answer_content'], 'md');
            $row['date']            = $row['post_date'];
            $row['post']            = PostModel::postId($row['answer_post_id']);
            $result[$ind]           = $row;
        }
        
        $data = [
            'h1'            => lang('Favorites') . ' ' . $login,
            'canonical'     => '***', 
            'sheet'         => 'favorites', 
            'meta_title'    => lang('Favorites'),
            'meta_desc'     => '***',
        ];

        return view(PR_VIEW_DIR . '/user/favorite', ['data' => $data, 'uid' => $uid, 'favorite' => $result]);   
    }
    
    // Удаление обложки
    function userCoverRemove()
    {
        $uid        = Base::getUid();
        $login      = \Request::get('login');
        $redirect   = '/u/' . $uid['login'] . '/setting/avatar';
        
        // Ошибочный Slug в Url
        if($login != $uid['login'] && $uid['trust_level'] != 5) {
            redirect($redirect);
        }

        $user = UserModel::getUserLogin($login);

        // Удалять может только автор и админ
        if ($user['id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }
        
        $path_img = HLEB_PUBLIC_DIR. '/uploads/users/cover/';

        // Удалим, кроме дефолтной
        if($user['cover_art'] != 'noavatar.png') {
            unlink($path_img . $user['cover_art']);
        }  
        
        UserModel::userCoverRemove($user['id']);
        
        Base::addMsg(lang('Cover removed'), 'success');
        redirect($redirect);
    }

    // Страница черновиков участника
    function userDrafts ()
    {
        $login  = \Request::get('login');
        
        $uid    = Base::getUid();
        $user   = UserModel::getUserLogin($uid['login']);

        // Ошибочный Slug в Url
        if($login != $uid['login']){
            redirect('/u/' . $user['login'] . '/drafts');
        }
  
        $drafts = UserModel::userDraftPosts($user['id']);
   
        $data = [
            'h1'            => lang('Drafts') . ' ' . $login,
            'canonical'     => '***',
            'sheet'         => 'drafts', 
            'meta_title'    => lang('Drafts'),
            'meta_desc'     => '***',
        ];

        return view(PR_VIEW_DIR . '/user/draft-post', ['data' => $data, 'uid' => $uid, 'drafts' => $drafts]);   
    }


    /////////// СИСТЕМА ИНВАЙТОВ ///////////
    
    // Показ формы инвайта
    public function invitePage()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Invite'),
            'canonical'     => '***',
            'sheet'         => 'invite', 
            'meta_title'    => lang('Invite'),
            'meta_desc'     => '***', 
        ];

        return view(PR_VIEW_DIR . '/user/invite', ['data' => $data, 'uid' => $uid]);    
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
        Base::PageError404($user);
        
        $Invitation = UserModel::InvitationResult($uid['id']);
 
        $data = [
            'h1'          => lang('Invites'),
            'canonical'     => '***',
            'sheet'         => 'invites', 
            'meta_title'    => lang('Invites'),
            'meta_desc'     => '***',            
        ];

        return view(PR_VIEW_DIR . '/user/invitation', ['data' => $data, 'uid' => $uid, 'user' => $user,  'result' => $Invitation]);  
    }
    
    // Создать инвайт
    function invitationCreate() 
    {
        // Данные участника
        $uid    = Base::getUid();
        
        $invitation_email = \Request::getPost('email');
        
        $redirect = '/u/' . $uid['login'] . '/invitation';
        
        if(!filter_var($invitation_email, FILTER_VALIDATE_EMAIL)) {
           Base::addMsg(lang('Invalid') . ' email', 'error');
           redirect($redirect);
        }
        
        $uInfo = UserModel::userInfo($invitation_email);
        if(!empty($uInfo['email'])) {
            
            if ($uInfo['email']) {
                Base::addMsg(lang('user-already'), 'error');
                redirect($redirect);
            }
        } 
        
        $inv_user = UserModel::InvitationOne($uid['id']);
 
        if($inv_user['invitation_email'] == $invitation_email) {
            Base::addMsg(lang('invate-to-replay'), 'error');
            redirect($redirect);
        }
        
        // + Повторная отправка
        
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = Base::randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();
        
        UserModel::addInvitation($uid['id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        Base::addMsg(lang('Invite created'), 'success');
        redirect($redirect); 
    }
    
}
