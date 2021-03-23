<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\AdminModel;
use Base;

class AdminController extends \MainController
{
	public function index()
	{
        // Если не авторизирован - редирект
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
        
         
        
        // Если TL участника не равен 5 (персонал) - редирект
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
            $row['logs_date']     = Base::ru_date($row['logs_date']);
            $row['updated_at']    = Base::ru_date($row['updated_at']);
            $result[$ind]         = $row;
         
        } 
        
        $uid  = Base::getUid();
        $data = [
            'title'        => 'Админка',
            'description'  => 'Админка на AreaDev',
            'users'        => $result,
        ];

         return view("admin/index", ['data' => $data, 'uid' => $uid]);
	}
    
    public function banUser() 
    {
      
        // Если не авторизирован - редирект
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
        
        // Если TL участника не равен 5 (персонал) - редирект
        if(!$isAdmin = UserModel::isAdmin($account['user_id'])) {
            redirect('/');
        }
        
        $user_id = Request::get('id');
        AdminModel::setBanUser($user_id);
        
        return true;
    }
    
}
