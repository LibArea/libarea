<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\SpaceModel;
use App\Models\PostModel;
use App\Models\UserModel;
use App\Models\WebModel;
use App\Models\AdminModel;
use App\Models\TopicModel;
use App\Models\CommentModel;
use App\Models\AnswerModel;
use App\Models\ContentModel;
use Lori\Content;
use Lori\Base;

class AdminController extends \MainController
{
	public function index()
	{
        $uid    = Base::getUid();
      
        $size = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes = number_format($size / 1048576, 2) . ' MB';
      
        $data = [
            'meta_title'    => lang('Admin'),
            'sheet'         => 'admin',
            'bytes'         => $bytes,
        ]; 
        
        return view(PR_VIEW_DIR . '/admin/index', ['data' => $data, 'uid' => $uid]);
	}
    
	public function users($sheet)
	{
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;

        $limit = 50;
        $pagesCount = AdminModel::getUsersListForAdminCount($sheet);
        $user_all   = AdminModel::getUsersListForAdmin($page, $limit, $sheet);

        $result = Array();
        foreach ($user_all as $ind => $row) {
            $row['replayIp']    = AdminModel::replayIp($row['reg_ip']);
            $row['isBan']       = AdminModel::isBan($row['id']);
            $row['logs']        = AdminModel::userLogId($row['id']);
            $row['created_at']  = lang_date($row['created_at']); 
            $result[$ind]       = $row;
        } 
        
        $title = lang('Users');
        if ($sheet == 'ban') {
            $title = lang('Banned');
        }
        
        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'users'         => $result,
            'meta_title'    => $title,
            'sheet'         => $sheet,
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/user/users', ['data' => $data, 'uid' => $uid, 'alluser' => $result]);
	}
    
    // Повторы IP
    public function logsIp() 
    {
        $uid        = Base::getUid();
        $user_ip    = \Request::get('ip');
        $user_all   = AdminModel::getUserLogsId($user_ip);
 
        $results = Array();
        foreach ($user_all as $ind => $row) {
            $row['replayIp']    = AdminModel::replayIp($row['reg_ip']);
            $row['isBan']       = AdminModel::isBan($row['id']);
            $results[$ind]      = $row;
        } 
        
        $data = [
            'h1'            => lang('Search'),
            'meta_title'    => lang('Search'),
            'sheet'         => 'admin',
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 

        return view(PR_VIEW_DIR . '/admin/user/logip', ['data' => $data, 'uid' => $uid, 'alluser' => $results]); 
    }
    
    // Бан участнику
    public function banUser() 
    {
        $user_id    = \Request::getPostInt('id');
        
        AdminModel::setBanUser($user_id);
        
        return true;
    }
    
    // Удалёные комментарии
    public function comments($sheet)
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;

        $limit = 100;
        $pagesCount = CommentModel::getCommentsDeletedCount();
        $comments   = CommentModel::getCommentsDeleted($page, $limit);

        $result = Array();
        foreach ($comments  as $ind => $row) {
            $row['content'] = Content::text($row['comment_content'], 'text');
            $row['date']    = lang_date($row['comment_date']);
            $result[$ind]   = $row;
        }
        
        $data = [
            'meta_title'    => lang('Deleted comments'),
            'sheet'         => 'comments',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
 
        return view(PR_VIEW_DIR . '/admin/comment-delet', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }
     
    // Удалёные ответы
    public function answers($sheet)
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;

        $limit = 100;
        $pagesCount = AnswerModel::getAnswersDeletedCount();
        $answers    = AnswerModel::getAnswersDeleted($page, $limit);

        $result = Array();
        foreach ($answers  as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }
        
        $data = [
            'meta_title'    => lang('Deleted answers'),
            'sheet'         => 'answers',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
 
        return view(PR_VIEW_DIR . '/admin/answer-delet', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }
     
    // Показываем дерево приглашенных
    public function invitations($sheet)
    {
        $uid    = Base::getUid();
        $invite = AdminModel::getInvitations();
 
        $result = Array();
        foreach ($invite  as $ind => $row) {
            $row['uid']         = UserModel::getUser($row['uid'], 'id');  
            $row['active_time'] = $row['active_time'];
            $result[$ind]       = $row;
        }

        $data = [
            'meta_title'    => lang('Invites'),
            'sheet'         => 'invitations',
        ]; 

        return view(PR_VIEW_DIR . '/admin/invitations', ['data' => $data, 'uid' => $uid, 'invitations' => $result]);
    }
    
    // Пространства
    public function spaces($sheet)
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;

        $limit = 25;
        $pagesCount = SpaceModel::getSpacesAllCount(); 
        $spaces     = SpaceModel::getSpacesAll($page, $limit, $uid['id'], 'all');
  
        $data = [
            'meta_title'    => lang('Spaces'),
            'sheet'         => 'admin',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
 
        return view(PR_VIEW_DIR . '/admin/spaces', ['data' => $data, 'uid' => $uid, 'spaces' => $spaces]);
    }
    
    // Удаление / восстановление пространства
    public function delSpace() 
    {
        $space_id   = \Request::getPostInt('id');
        
        SpaceModel::SpaceDelete($space_id);
       
        return true;
    }
    
    // Все награды
    public function badges($sheet)
    {
        $uid    = Base::getUid();
        $badges = AdminModel::getBadgesAll();
        
        $data = [
            'meta_title'    => lang('Badges'),
            'sheet'         => 'badges',
        ]; 

        return view(PR_VIEW_DIR . '/admin/badge/badges', ['data' => $data, 'uid' => $uid, 'badges' => $badges]);
    }
    
    // Форма добавления награды
    public function addBadgeForm()
    {
        $uid  = Base::getUid();
        $data = [
            'meta_title'    => lang('Add badge'),
            'sheet'         => 'badges',
        ]; 

        return view(PR_VIEW_DIR . '/admin/badge/add', ['data' => $data, 'uid' => $uid]);
    }
    
    // Форма награждения участинка
    public function addBadgeUserForm()
    {
        $uid        = Base::getUid();
        $user_id    = \Request::getInt('id');

        if ($user_id > 0) {
            $user   = UserModel::getUser($user_id, 'id');
        } else {
            $user   = null;
        }

        $badges = AdminModel::getBadgesAll();
        
        $data = [
            'meta_title'    => lang('Reward the user'),
            'sheet'         => 'admin',
        ]; 

        return view(PR_VIEW_DIR . '/admin/badge/user-add', ['data' => $data, 'uid' => $uid, 'user' => $user, 'badges' => $badges]);    
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
    public function editBadgeForm()
    {
        $uid        = Base::getUid();
        $badge_id   = \Request::getInt('id');
        $badge      = AdminModel::getBadgeId($badge_id);        

        if (!$badge['badge_id']) {
            redirect('/admin/badges');
        }

        $data = [
            'meta_title'    => lang('Edit badge'),
            'sheet'         => 'admin',
        ]; 

        return view(PR_VIEW_DIR . '/admin/badge/edit', ['data' => $data, 'uid' => $uid, 'badge' => $badge]);
    }
    
    // Измененяем награду
    public function badgeEdit()
    {
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
        $uid        = Base::getUid();
        $user_id    = \Request::getInt('id');
        
        if (!$user = UserModel::getUser($user_id, 'id')) {
           redirect('/admin'); 
        }
        
        $user['isBan']      = AdminModel::isBan($user_id);
        $user['replayIp']   = AdminModel::replayIp($user_id);
        $user['logs']       = AdminModel::userLogId($user_id);
        $user['badges']     = UserModel::getBadgeUserAll($user_id);
         
        $data = [
            'meta_title'        => lang('Edit user'),
            'sheet'             => 'edit-user',
            'posts_count'       => UserModel::contentCount($user['id'], 'posts'),
            'answers_count'     => UserModel::contentCount($user['id'], 'answers'),
            'comments_count'    => UserModel::contentCount($user['id'], 'comments'), 
            'spaces_user'       => SpaceModel::getUserCreatedSpaces($user_id),
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/user/edit', ['data' => $data, 'uid' => $uid, 'user' => $user]);
    }
    
    // Редактировать участника
    public function userEdit()
    {
        $user_id    = \Request::getInt('id');
        
        $redirect = '/admin';
        if (!UserModel::getUser($user_id, 'id')) {
            redirect($redirect);
        }
        
        $email          = \Request::getPost('email');
        $login          = \Request::getPost('login');
        $name           = \Request::getPost('name');
        $about          = \Request::getPost('about');
        $activated      = \Request::getPostInt('activated');
        $trust_level    = \Request::getPostInt('trust_level');
        $website        = \Request::getPost('website');
        $location       = \Request::getPost('location');
        $public_email   = \Request::getPost('public_email');
        $skype          = \Request::getPost('skype');
        $twitter        = \Request::getPost('twitter');
        $telegram       = \Request::getPost('telegram');
        $vk             = \Request::getPost('vk');
        
        Base::Limits($login, lang('Login'), '4', '11', $redirect);
        
        $data = [
            'id'            => $user_id,
            'email'         => $email,
            'login'         => $login,
            'name'          => empty($name) ? '' : $name,
            'activated'     => $activated,
            'trust_level'   => $trust_level,
            'about'         => empty($about) ? '' : $about,
            'website'       => empty($website) ? '' : $website,
            'location'      => empty($location) ? '' : $location,
            'public_email'  => empty($public_email) ? '' : $public_email,
            'skype'         => empty($skype) ? '' : $skype,
            'twitter'       => empty($twitter) ? '' : $twitter,
            'telegram'      => empty($telegram) ? '' : $telegram,
            'vk'            => empty($vk) ? '' : $vk,
        ];
        
        AdminModel::setUserEdit($data);
        
        redirect($redirect);
    }
    
    // Домены в системе
    public function domains()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;
        
        $limit  = 25;
        $pagesCount = WebModel::getLinksAllCount();  
        $domains    = WebModel::getLinksAll($page, $limit, $uid['id']);
        
        $data = [
            'meta_title'    => lang('Domains'),
            'sheet'         => 'domains',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/domains', ['data' => $data, 'uid' => $uid, 'domains' => $domains]);
    }
    
    // Страница стоп-слов
    public function words($sheet)
    {
        $uid    = Base::getUid();
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $words = ContentModel::getStopWords();

        $data = [
            'h1'            => lang('Stop words'),
            'meta_title'    => lang('Stop words'),
            'sheet'         => 'words',
        ]; 

        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/word/words', ['data' => $data, 'uid' => $uid, 'words' => $words]);
    }
    
    // Все пространства
    public function topics($sheet) 
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;
        
        $limit  = 55;
        $pagesCount = TopicModel::getTopicsAllCount(); 
        $topics     = TopicModel::getTopicsAll($page, $limit);
        
        $data = [
            'meta_title'    => lang('Topics'),
            'sheet'         => 'topics',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ]; 
        
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/topics', ['data' => $data, 'uid' => $uid, 'topics' => $topics]);
    }
    
    // Форма добавления стоп-слова
    public function wordsAddForm()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Add a stop word'),
            'meta_title'    => lang('Add a stop word'),
            'sheet'         => 'words',
        ]; 

        return view(PR_VIEW_DIR . '/admin/word/add-word', ['data' => $data, 'uid' => $uid]);
    }
    
    // Добавление стоп-слова
    public function wordAdd()
    {
        $word = \Request::getPost('word');
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
        $word_id    = \Request::getPostInt('id');

        ContentModel::deleteStopWord($word_id);
        
        redirect('/admin/words');  
    }
    
    public function audit($sheet) 
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;
        
        $limit  = 55;
        $pagesCount = AdminModel::getAuditsAllCount($sheet); 
        $audits     = AdminModel::getAuditsAll($page, $limit, $sheet);
        
        $result = Array();
        foreach ($audits  as $ind => $row) {
            
            if ($row['audit_type'] == 'post') {
                $row['content'] = PostModel::getPostId($row['audit_content_id']);
            } elseif ($row['audit_type'] == 'answer') {
                $row['content'] = AnswerModel::getAnswerId($row['audit_content_id']); 
            } elseif ($row['audit_type'] == 'comment') {
                $row['content'] = CommentModel::getCommentsId($row['audit_content_id']); 
            }

            $result[$ind]       = $row;
        }
        
        $data = [
            'meta_title'    => lang('Audit'),
            'sheet'         => $sheet,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ]; 
        
        Request::getResources()->addBottomScript('/assets/js/admin.js'); 
        
        return view(PR_VIEW_DIR . '/admin/audits', ['data' => $data, 'uid' => $uid, 'audits' => $result]);
    } 
    
    // Восстановление после аудита
    public function status() 
    {
        $st     = \Request::getPost('status');
        $status = preg_split('/(@)/', $st);
       
        // id, type
        AdminModel::recoveryAudit($status[0], $status[1]);
      
        return true;
    }
 
}
