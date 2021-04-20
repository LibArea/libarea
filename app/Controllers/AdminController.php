<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\SpaceModel;
use App\Models\AdminModel;
use Parsedown;
use Base;

class AdminController extends \MainController
{
	public function index()
	{
        // Если TL участника не равен 5 (персонал) - редирект
        $account = Request::getSession('account');
        if(!$isAdmin = UserModel::isAdmin($account['user_id'])) {
            redirect('/');
        }
      
        $user_all = AdminModel::UsersAll();
        
        $result = Array();
        foreach($user_all as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['avatar']        = $row['avatar'];  
            $row['replayIp']      = AdminModel::replayIp($row['reg_ip']);
            $row['isBan']         = AdminModel::isBan($row['id']);
            $row['created_at']    = Base::ru_date($row['created_at']); 
            $row['logs_date']     = Base::ru_date(empty($row['logs_date']));
            $row['updated_at']    = Base::ru_date($row['updated_at']);
            $result[$ind]         = $row;
         
        } 
        
        $uid  = Base::getUid();
        $data = [
            'title'        => 'Последние сессии | Админка',
            'description'  => 'Админка на AreaDev',
            'users'        => $result,
        ];

        return view(PR_VIEW_DIR . '/admin/index', ['data' => $data, 'uid' => $uid, 'alluser' => $result]);
	}
    
    public function banUser() 
    {
        // Если TL участника не равен 5 (персонал) - редирект
        $account = Request::getSession('account');
        if(!$isAdmin = UserModel::isAdmin($account['user_id'])) {
            redirect('/');
        }
        
        $user_id = Request::get('id');
        AdminModel::setBanUser($user_id);
        
        return true;
    }
    
    // Удаленые комментарии
    public function Comments ()
    {
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
         
        $comm = AdminModel::getCommentsDell();
 
        $account    = \Request::getSession('account');
        $user_id    = $account ? $account['user_id'] : 0;
 
        $result = Array();
        foreach($comm  as $ind => $row){
            if(!$row['avatar']) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['avatar']  = $row['avatar'];
            $row['content'] = $Parsedown->text($row['comment_content']);
            $row['date']    = Base::ru_date($row['comment_date']);
            $result[$ind]   = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'          => 'Удаленные комментарии',
            'title'       => 'Удаленные комментарии' . ' | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Все удаленные комментарии на сайте в порядке очередности. ' . $GLOBALS['conf']['sitename'],
        ]; 
 
        return view(PR_VIEW_DIR . '/admin/comm_del', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }
     
    // Удаление комментария
    public function recoverComment()
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $comm_id = \Request::getPostInt('comm_id');
        
        AdminModel::CommentsRecover($comm_id);
        
        return true;
    }
    
    // Показываем дерево приглашенных
    public function Invitations ()
    {
        $invite     = AdminModel::getInvitations();
        $account    = \Request::getSession('account');
        $user_id    = $account ? $account['user_id'] : 0;
 
        $result = Array();
        foreach($invite  as $ind => $row){
            if(!$row['avatar']) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['uid']         = UserModel::getUserId($row['uid']);  
            $row['active_time'] = Base::ru_date($row['active_time']);
            $row['avatar']      = $row['avatar'];
            $result[$ind]       = $row;
        }

        $uid  = Base::getUid();
        $data = [
            'h1'          => 'Инвайты',
            'title'       => 'Инвайты' . ' | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Дерево инвайтов. ' . $GLOBALS['conf']['sitename'],
        ]; 
 
        return view(PR_VIEW_DIR . '/admin/invitations', ['data' => $data, 'uid' => $uid, 'invitations' => $result]);
    }
    
    // Для дерева инвайтов
    private function invatesTree($active_uid, $level, $invitations, $tree=array()){
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
    public function Space ()
    {
        $account    = \Request::getSession('account');
        $user_id    = $account ? $account['user_id'] : 0;
        $space      = AdminModel::getAdminSpaceAll($user_id);
         
        $result = Array();
        foreach($space  as $ind => $row){
            
            if(!$row['space_img'] ) {
                $row['space_img'] = 'space_no.png';
            } 

           if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
           } 

            $space['space_img'] = $row['space_img'];
            $result[$ind]       = $row;
        }

        $uid  = Base::getUid();
        $data = [
            'h1'          => 'Пространства',
            'title'       => 'Пространства' . ' | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Пространства. ' . $GLOBALS['conf']['sitename'],
        ]; 
 
        return view(PR_VIEW_DIR . '/admin/space', ['data' => $data, 'uid' => $uid, 'space' => $result]);
    }
    
    // Добавить пространство администратору
    public function addAdminSpacePage() 
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }  
        
        $uid  = Base::getUid();
        $data = [
            'h1'          => 'Добавить пространство',
            'title'       => 'Добавить пространство' . ' | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Добавить пространство. ' . $GLOBALS['conf']['sitename'],
        ]; 
        
        return view(PR_VIEW_DIR . '/admin/add-space', ['data' => $data, 'uid' => $uid]);
    }
    
    // Удаление / восстановление пространства
    public function delSpace() {
        
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }   
        
        $space_id = \Request::getPostInt('space_id');

        SpaceModel::SpaceDelete($space_id);
       
        return true;
    }
    
}
