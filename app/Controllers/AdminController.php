<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\SpaceModel;
use App\Models\AdminModel;
use Lori\Base;

class AdminController extends \MainController
{
	public function index()
	{
        // Доступ только персоналу
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }
      
        $user_all = AdminModel::UsersAll();
        
        $result = Array();
        foreach($user_all as $ind => $row) {
            $row['replayIp']      = AdminModel::replayIp($row['reg_ip']);
            $row['isBan']         = AdminModel::isBan($row['id']);
            $row['created_at']    = Base::ru_date($row['created_at']); 
            $row['logs_date']    = Base::ru_date($row['logs_date']);
            $result[$ind]         = $row;
        } 
        
        $data = [
            'h1'    => lang('Admin'),
            'users' => $result,
        ]; 
        
        // title, description
        Base::Meta(lang('Admin'), lang('Admin'), $other = false);

        return view(PR_VIEW_DIR . '/admin/index', ['data' => $data, 'uid' => $uid, 'alluser' => $result]);
	}
    
    // Бан участнику
    public function banUser() 
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }

        $user_id = \Request::getPostInt('id');
        AdminModel::setBanUser($user_id);
        
        return true;
    }
    
    // Удаленые комментарии
    public function comments ()
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }
         
        $comm = AdminModel::getCommentsDell();

        $result = Array();
        foreach($comm  as $ind => $row){
            $row['content'] = Base::Markdown($row['comment_content']);
            $row['date']    = Base::ru_date($row['comment_date']);
            $result[$ind]   = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1' => lang('Deleted comments'),
        ]; 
 
        Base::Meta(lang('Deleted comments'), lang('Deleted comments'), $other = false);
 
        return view(PR_VIEW_DIR . '/admin/comm_del', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }
     
    // Удаление комментария
    public function recoverComment()
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }
        
        $comm_id = \Request::getPostInt('id');
        AdminModel::CommentsRecover($comm_id);
        
        return true;
    }
    
    // Показываем дерево приглашенных
    public function invitations ()
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }  
 
        $invite     = AdminModel::getInvitations();
 
        $result = Array();
        foreach($invite  as $ind => $row){
            $row['uid']         = UserModel::getUserId($row['uid']);  
            $row['active_time'] = $row['active_time'];
            $result[$ind]       = $row;
        }

        $data = [
            'h1'    => lang('Invites'),
        ]; 
 
        Base::Meta(lang('Invites'), lang('Invites'), $other = false);
 
        return view(PR_VIEW_DIR . '/admin/invitations', ['data' => $data, 'uid' => $uid, 'invitations' => $result]);
    }
    
    // Для дерева инвайтов
    private function invatesTree($active_uid, $level, $invitations, $tree=array())
    {
        $level++;
        foreach($invitations as $invitation){
            if ($invitation['uid'] == $uid){
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
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }  
        
        $space = AdminModel::getAdminSpaceAll($uid['id']);
  
        $data = [
            'h1' => lang('Space'),
        ]; 
 
        Base::Meta(lang('Space'), lang('Space'), $other = false);
 
        return view(PR_VIEW_DIR . '/admin/space', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Форма добавить пространство
    public function addSpacePage() 
    {
        $uid  = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }  
        
        $data = [
            'h1' => lang('Add Space'),
        ]; 
 
        Base::Meta(lang('Add Space'), lang('Add Space'), $other = false);
        
        return view(PR_VIEW_DIR . '/admin/add-space', ['data' => $data, 'uid' => $uid]);
    }
    
    // Удаление / восстановление пространства
    public function delSpace() 
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }   
        
        $space_id = \Request::getPostInt('id');
        SpaceModel::SpaceDelete($space_id);
       
        return true;
    }
    
    // Добавить пространства
    public function spaceAdd() 
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        } 
        
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
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }
        
        $badges = AdminModel::getBadgesAll();
        
        $data = [
            'h1' => lang('Badges'),
        ]; 
 
        Base::Meta(lang('Badges'), lang('Badges'), $other = false);
        
        return view(PR_VIEW_DIR . '/admin/badges', ['data' => $data, 'uid' => $uid, 'badges' => $badges]);
    }
    
    // Форма добавления награды
    public function addBadgePage()
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }
        
        $data = [
            'h1' => lang('Add badge'),
        ]; 
 
        Base::Meta(lang('Add badge'), lang('Add badge'), $other = false);
        
        return view(PR_VIEW_DIR . '/admin/badge-add', ['data' => $data, 'uid' => $uid]);
    }
    
    // Форма изменения награды
    public function badgeEditPage()
    {
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect('/');
        }
        
        $badge_id   = \Request::getInt('id');
        $badge      = AdminModel::getBadgeId($badge_id);        

        if (!$badge['badge_id']) {
            redirect('/admin/badges');
        }

        $data = [
            'h1' => lang('Edit badge'),
        ]; 
 
        Base::Meta(lang('Edit badge'), lang('Edit badge'), $other = false);
        
        return view(PR_VIEW_DIR . '/admin/badge-edit', ['data' => $data, 'uid' => $uid, 'badge' => $badge]);
    }
    
    // Измененяем награду
    public function badgeEdit()
    {
        $redirect = '/admin/badges';
        
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect($redirect);
        }
        
        $badge_id   = \Request::getInt('id');
        $badge      = AdminModel::getBadgeId($badge_id); 
        
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
    
    // Измененяем награду
    public function badgeAdd()
    {
        $redirect = '/admin/badges';
        
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            redirect($redirect);
        }
        
        $badge_title         = \Request::getPost('badge_title');
        $badge_description   = \Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // не фильтруем

        Base::Limits($badge_title, lang('Title'), '4', '250', $redirect);
        Base::Limits($badge_description, lang('Description'), '12', '250', $redirect);
        Base::Limits($badge_icon, lang('Icon'), '12', '550', $redirect);
        
        $data = [
            'badge_title'       => $badge_title,
            'badge_description' => $badge_description,
            'badge_icon'        => $badge_icon,
        ];
        
        AdminModel::setAddBadge($data);
        redirect($redirect);  
    }
    
}
