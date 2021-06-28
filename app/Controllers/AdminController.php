<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\SpaceModel;
use App\Models\UserModel;
use App\Models\LinkModel;
use App\Models\AdminModel;
use App\Models\CommentModel;
use App\Models\AnswerModel;
use App\Models\ContentModel;
use Lori\Content;
use Lori\Base;

class AdminController extends \MainController
{
	public function index($sheet)
	{
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
 
        // Доступ только персоналу
        $uid = self::isAdmin();

        $pagesCount = AdminModel::usersCount($page, $sheet);
        $user_all   = AdminModel::usersAll($page, $sheet);

        $result = Array();
        foreach ($user_all as $ind => $row) {
            $row['replayIp']    = AdminModel::replayIp($row['reg_ip']);
            $row['isBan']       = AdminModel::isBan($row['id']);
            $row['logs']        = AdminModel::userLogId($row['id']);
            $row['created_at']  = lang_date($row['created_at']); 
            $result[$ind]       = $row;
        } 
        
        $data = [
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
            'users'         => $result,
            'meta_title'    => lang('Admin'),
            'sheet'         => $sheet,
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/index', ['data' => $data, 'uid' => $uid, 'alluser' => $result]);
	}
    
    // Повторы IP
    public function logsIp() 
    {
        $uid        = self::isAdmin();
        $user_ip    = \Request::get('ip');
        $user_all   = AdminModel::getUserLogsId($user_ip);
 
        $results = Array();
        foreach ($user_all as $ind => $row) {
            $row['replayIp']    = AdminModel::replayIp($row['reg_ip']);
            $row['isBan']       = AdminModel::isBan($row['id']);
            $results[$ind]      = $row;
        } 
        
        $flow_ip     = AdminModel::replayFlowIp($user_ip);
        $flows = Array();
        foreach ($flow_ip as $ind => $row) {
            $flows[$ind]      = $row;
        } 

        $data = [
            'h1'            => lang('Search'),
            'meta_title'    => lang('Search'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 

        return view(PR_VIEW_DIR . '/admin/logip', ['data' => $data, 'uid' => $uid, 'alluser' => $results, 'flows' => $flows]); 
    }
    
    // Бан участнику
    public function banUser() 
    {
        $uid = self::isAdmin();
        
        $user_id    = \Request::getPostInt('id');
        AdminModel::setBanUser($user_id);
        
        return true;
    }
    
    // Удалёные комментарии
    public function comments()
    {
        $uid    = self::isAdmin();
        $comm   = CommentModel::getCommentsDeleted();

        $result = Array();
        foreach ($comm  as $ind => $row) {
            $row['content'] = Content::text($row['comment_content'], 'text');
            $row['date']    = lang_date($row['comment_date']);
            $result[$ind]   = $row;
        }
        
        $data = [
            'meta_title'    => lang('Deleted comments'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
 
        return view(PR_VIEW_DIR . '/admin/comment_delet', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }
     
    // Удалёные ответы
    public function answers()
    {
        $uid        = self::isAdmin();
        $answers    = AnswerModel::getAnswersDell();

        $result = Array();
        foreach ($answers  as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }
        
        $data = [
            'meta_title'    => lang('Deleted answers'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
 
        return view(PR_VIEW_DIR . '/admin/answer_delet', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }
     
    // Показываем дерево приглашенных
    public function invitations()
    {
        $uid    = self::isAdmin();
        $invite = AdminModel::getInvitations();
 
        $result = Array();
        foreach ($invite  as $ind => $row) {
            $row['uid']         = UserModel::getUserId($row['uid']);  
            $row['active_time'] = $row['active_time'];
            $result[$ind]       = $row;
        }

        $data = [
            'meta_title'    => lang('Invites'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
 
        return view(PR_VIEW_DIR . '/admin/invitations', ['data' => $data, 'uid' => $uid, 'invitations' => $result]);
    }
    
    // Для дерева инвайтов
    private function invatesTree($active_uid, $level, $invitations, $tree=array())
    {
        $level++;
        foreach ($invitations as $invitation) {
            if ($invitation['uid'] == $uid) {
                $invitation['level'] = $level-1;
                $tree[] = $invitation;
                $tree = $this->invatesTree($invitation['active_uid'], $level, $invitations, $tree);
            }
        }
		return $tree;
    }
    
    // Пространства
    public function spaces()
    {
        $uid    = self::isAdmin();
        $spaces = AdminModel::getAdminSpaceAll($uid['id']);
  
        $data = [
            'meta_title'    => lang('Spaces'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
 
        return view(PR_VIEW_DIR . '/admin/space/spaces', ['data' => $data, 'uid' => $uid, 'spaces' => $spaces]);
    }
    
    // Форма добавить пространство
    public function addSpacePage() 
    {
        $uid = self::isAdmin();
        
        $data = [
            'meta_title'    => lang('Add Space'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/space/add-space', ['data' => $data, 'uid' => $uid]);
    }
    
    // Удаление / восстановление пространства
    public function delSpace() 
    {
        $uid        = self::isAdmin();
        $space_id   = \Request::getPostInt('id');
        
        SpaceModel::SpaceDelete($space_id);
       
        return true;
    }
    
    // Добавить пространства
    public function spaceAdd() 
    {
        $uid = self::isAdmin();
        
        $space_slug         = \Request::getPost('space_slug');
        $space_name         = \Request::getPost('space_name');  
        $space_permit       = \Request::getPostInt('permit');
        $meta_desc          = \Request::getPost('space_description');
        $space_text         = \Request::getPost('space_text'); 
        $space_short_text   = \Request::getPost('space_short_text');
        $space_feed         = \Request::getPostInt('space_feed');
        $space_tl           = \Request::getPostInt('space_tl');
        
        $redirect = '/admin/space/add';

        if (!preg_match('/^[a-zA-Z0-9]+$/u', $space_slug))
        {
            Base::addMsg('В URL можно использовать только латиницу, цифры', 'error');
            redirect($redirect);
        }

        Base::Limits($space_slug, lang('URL'), '4', '20', $redirect);

        if (SpaceModel::getSpaceInfo($space_slug)) {
            Base::addMsg('Такой URL пространства уже есть', 'error');
            redirect($redirect);
        }
 
        Base::Limits($space_name, lang('Title'), '6', '25', $redirect);
        Base::Limits($meta_desc, lang('Meta-'), '60', '225', $redirect);
        Base::Limits($space_text, lang('Sidebar-'), '6', '512', $redirect);
        Base::Limits($space_short_text, 'TEXT', '20', '250', $redirect);
        
        $space_permit   = $space_permit == 1 ? 1 : 0;
        $space_feed     = $space_feed == 1 ? 1 : 0;
        $space_tl       = $space_tl == 1 ? 1 : 0;
        
        $data = [
            'space_name'            => $space_name,
            'space_slug'            => $space_slug,
            'space_description'     => $meta_desc,
            'space_color'           => '#333',
            'space_img'             => 'space_no.png',
            'space_text'            => $space_text,
            'space_short_text'      => $space_short_text,
            'space_date'            => date("Y-m-d H:i:s"),
            'space_category_id'     => 1,
            'space_user_id'         => $uid['id'],
            'space_type'            => 0, 
            'space_permit_users'    => $space_permit,
            'space_feed'            => $space_feed,
            'space_tl'              => $space_tl,
            'space_is_delete'       => 0,
        ];
 
        SpaceModel::AddSpace($data);

        redirect('/admin/spaces');
    }
    
    // Все награды
    public function Badges()
    {
        $uid    = self::isAdmin();
        $badges = AdminModel::getBadgesAll();
        
        $data = [
            'meta_title'    => lang('Badges'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/badge/badges', ['data' => $data, 'uid' => $uid, 'badges' => $badges]);
    }
    
    // Форма добавления награды
    public function addBadgeForm()
    {
        $uid = self::isAdmin();
        
        $data = [
            'meta_title'    => lang('Add badge'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/badge/badge-add', ['data' => $data, 'uid' => $uid]);
    }
    
    // Форма награждения участинка
    public function addBadgeUserPage()
    {
        $uid = self::isAdmin();
        
        $user_id    = \Request::getInt('id');
        
        if ($user_id > 0) {
            $user   = UserModel::getUserId($user_id);
        } else {
            $user   = null;
        }
        
        $badges = AdminModel::getBadgesAll();
        
        $data = [
            'meta_title'    => lang('Reward the user'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/badge/badge-user-add', ['data' => $data, 'uid' => $uid, 'user' => $user, 'badges' => $badges]);    
    }
    
    // Награждение
    public function addBadgeUser()
    {
        $user_id    = \Request::getPostInt('user_id');
        $badge_id   = \Request::getPostInt('badge_id');

        AdminModel::badgeUserAdd($user_id, $badge_id);

        Base::addMsg(lang('Reward added'), 'success');
        redirect('/admin/user/' . $user_id . '/edit');
    }

    // Форма изменения награды
    public function badgeEditForm()
    {
        $uid        = self::isAdmin();
        $badge_id   = \Request::getInt('id');
        $badge      = AdminModel::getBadgeId($badge_id);        

        if (!$badge['badge_id']) {
            redirect('/admin/badges');
        }

        $data = [
            'meta_title'    => lang('Edit badge'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/badge/badge-edit', ['data' => $data, 'uid' => $uid, 'badge' => $badge]);
    }
    
    // Измененяем награду
    public function badgeEdit()
    {
        $uid        = self::isAdmin();
        $badge_id   = \Request::getInt('id');
        $badge      = AdminModel::getBadgeId($badge_id);
        
        $redirect = '/admin/badges';
        if (!$badge['badge_id']) {
            redirect($redirect);
        }
        
        $badge_title         = \Request::getPost('badge_title');
        $badge_description   = \Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // не фильтруем

        Base::Limits($badge_title, lang('Title'), '4', '250', $redirect);
        Base::Limits($badge_description, lang('Description'), '12', '250', $redirect);
        Base::Limits($badge_icon, lang('Icon'), '12', '550', $redirect);
        
        $data = [
            'badge_id'          => $badge_id,
            'badge_title'       => $badge_title,
            'badge_description' => $badge_description,
            'badge_icon'        => $badge_icon,
        ];
        
        AdminModel::setEditBadge($data);
        redirect($redirect);  
    }
    
    // Добавляем награду
    public function badgeAdd()
    {
        $uid = self::isAdmin();
        
        $badge_title         = \Request::getPost('badge_title');
        $badge_description   = \Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // не фильтруем

        $redirect = '/admin/badges';
        Base::Limits($badge_title, lang('Title'), '4', '250', $redirect);
        Base::Limits($badge_description, lang('Description'), '12', '250', $redirect);
        Base::Limits($badge_icon, lang('Icon'), '12', '550', $redirect);
        
        $data = [
            'badge_title'       => $badge_title,
            'badge_description' => $badge_description,
            'badge_icon'        => $badge_icon,
            'badge_tl'          => 0,
            'badge_score'       => 0,
        ];
        
        AdminModel::setAddBadge($data);
        redirect($redirect);  
    }
    
    // Страница редактиорование участника
    public function userEditPage()
    {
        $uid        = self::isAdmin();
        $user_id    = \Request::getInt('id');
        
        $redirect = '/admin';
        if (!$user = UserModel::getUserId($user_id)) {
           redirect($redirect); 
        }
        
        $user['isBan']      = AdminModel::isBan($user_id);
        $user['replayIp']   = AdminModel::replayIp($user_id);
        $user['logs']       = AdminModel::userLogId($user_id);
        $user['badges']     = UserModel::getBadgeUserAll($user_id);
         
        $data = [
            'meta_title'    => lang('Edit user'),
            'sheet'         => 'admin',
            'post_num_user' => UserModel::userPostsNum($user_id),
            'answ_num_user' => UserModel::userAnswersNum($user_id),
            'comm_num_user' => UserModel::userCommentsNum($user_id), 
            'space_user'    => SpaceModel::getSpaceUserId($user_id),
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/user-edit', ['data' => $data, 'uid' => $uid, 'user' => $user]);
    }
    
    // Редактировать участника
    public function userEdit()
    {
        $uid        = self::isAdmin();
        $user_id    = \Request::getInt('id');
        
        $redirect = '/admin';
        if (!UserModel::getUserId($user_id)) {
            redirect($redirect);
        }
        
        $email          = \Request::getPost('email');
        $login          = \Request::getPost('login');
        $name           = \Request::getPost('name');
        $about          = \Request::getPost('about');
        $trust_level    = \Request::getPostInt('trust_level');

        $website        = \Request::getPost('website');
        $location       = \Request::getPost('location');
        $public_email   = \Request::getPost('public_email');
        $skype          = \Request::getPost('skype');
        $twitter        = \Request::getPost('twitter');
        $telegram       = \Request::getPost('telegram');
        $vk             = \Request::getPost('vk');
        
        // См. https://github.com/Respect/Validation
        Base::Limits($login, lang('Login'), '4', '11', $redirect);
        
        $name           = empty($name) ? '' : $name;
        $about          = empty($about) ? '' : $about;
        $website        = empty($website) ? '' : $website;
        $location       = empty($location) ? '' : $location;
        $public_email   = empty($public_email) ? '' : $public_email;
        $skype          = empty($skype) ? '' : $skype;
        $twitter        = empty($twitter) ? '' : $twitter;
        $telegram       = empty($telegram) ? '' : $telegram;
        $vk             = empty($vk) ? '' : $vk;
        
        AdminModel::setUserEdit($user_id, $email, $login, $name, $about, $trust_level, $website, $location, $public_email, $skype, $twitter, $telegram, $vk);
        
        redirect($redirect);
    }
    
    // Домены в системе
    public function domains()
    {
        $uid    = self::isAdmin();
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $domains    = LinkModel::getLinksAll($uid['id'], $page);
        $data = [
            'meta_title'    => lang('Domains'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/domain/domains', ['data' => $data, 'uid' => $uid, 'domains' => $domains]);
    }
    
    // Форма редактирование домена
    public function editDomain()
    {
        $uid        = self::isAdmin();
        $domain_id  = \Request::getInt('id');
        
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $domain = LinkModel::getLinkId($domain_id);

        $data = [
            'meta_title'    => lang('Change the domain') .' | '. $domain['link_url_domain'],
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/domain/domain-edit', ['data' => $data, 'uid' => $uid, 'domain' => $domain]);
    }
    
    // Изменение домена
    public function domainEdit()
    {
        $uid        = self::isAdmin();
        $domain_id    = \Request::getInt('id');
        
        $redirect = '/admin/domains';
        if (!AdminModel::getLinkIdOne($domain_id)) {
            redirect($redirect);
        }
        
        $link_url           = \Request::getPost('link_url');
        $link_title         = \Request::getPost('link_title');
        $link_content       = \Request::getPost('link_content');

        Base::Limits($link_title , lang('Title'), '24', '250', $redirect);
        Base::Limits($link_content, lang('Description'), '24', '1500', $redirect);
        
        $about          = empty($about) ? '' : $about;
        $website        = empty($website) ? '' : $website;
        
        LinkModel::setLinkEdit($domain_id, $link_url, $link_title, $link_content);
        
        redirect($redirect);
    }
    
    // Страница стоп-слов
    public function words()
    {
        $uid        = self::isAdmin();
        
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $words = UserModel::getStopWords();

        $data = [
            'h1'            => lang('Stop words'),
            'meta_title'    => lang('Stop words'),
            'sheet'         => 'words',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/word/words', ['data' => $data, 'uid' => $uid, 'words' => $words]);
    }
    
    
    // Форма добавления стоп-слова
    public function wordsAddForm()
    {
        $uid        = self::isAdmin();
        
        $data = [
            'h1'            => lang('Add a stop word'),
            'meta_title'    => lang('Add a stop word'),
            'sheet'         => 'add-words',
        ]; 

        Request::getResources()->addBottomStyles('/assets/css/admin.css');
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/word/add-word', ['data' => $data, 'uid' => $uid]);
    }
    
    // Добавление стоп-слова
    public function createWord()
    {
        $uid        = self::isAdmin();
        $word       = \Request::getPost('word');
        
        $data = [
            'stop_word'     => $word,
            'stop_add_uid'  => 1,
            'stop_space_id' => 0, // Глобально
        ];

        ContentModel::setStopWord($data);
        
        redirect('/admin/words');  
    }
    
    // Удаление стоп-слова
    public function deleteWord()
    {
        $uid        = self::isAdmin();
        $word_id    = \Request::getPostInt('id');

        ContentModel::deleteStopWord($word_id);
        
        redirect('/admin/words');  
    }
    
    // Проверка прав
    public static function isAdmin()
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }
        return $uid;
    }
}
