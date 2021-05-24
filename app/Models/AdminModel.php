<?php

namespace App\Models;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;
use DB;
use PDO;

class AdminModel extends \MainModel
{
    // Страница участников
    public static function UsersAll()
    {
        $sql = "SELECT users.*, users_logs.*
                fROM users, users_logs WHERE logs_user_id = id
                ORDER BY logs_date DESC LIMIT 55"; // GROUP BY id 

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 

    }

    // Страница участников
    public static function UsersLogAll($id)
    {
        return XD::select('*')->from(['users_logs'])->where(['logs_user_id'], '=', $id)->getSelectOne();

    }
    
    // Получение информации по id
    public static function UserId($uid)
    {
        return XD::select('*')
                ->from(['users'])
                ->where(['id'], '=', $uid)->getSelectOne();
    }

    // Проверка IP на дубликаты
    public static function replayIp($ip)
    {
        if(!$ip) { return 0; }

        $query = XD::select('*')
                ->from(['users'])
                ->where(['reg_ip'], '=', $ip);

        return count($query->getSelect());
    }
    
    // Находит ли пользователь в бан- листе и рабанен ли был он
    public static function isBan($uid)
    {
        $result = XD::select('*')->from(['users_banlist'])
                ->where(['banlist_user_id'], '=', $uid)
                ->and(['banlist_status'], '=', 1)->getSelectOne();

        if(!$result) {
            return false;    
        }

        return true;   
    }
    
    
    public static function setBanUser($uid)
    {
        $res = XD::select('*')->from(['users_banlist'])
                ->where(['banlist_user_id'], '=', $uid)->getSelect();
                
        $num = count($res);
     
        if($num != 0) { 
        
            $result = Array();
            foreach($res as $row){
                $status = $row['banlist_status'];
            }  

            if($status == 0) {   
            	// Забанить повторно
                // Проставляем в banlist_int_num 2, что пока означет: возможно > 2
                XD::update(['users_banlist'])->set(['banlist_int_num'], '=', 2, ',', ['banlist_status'], '=', 1)->where(['banlist_user_id'], '=', $uid)->run(); 
                XD::update(['users'])->set(['ban_list'], '=', 1)->where(['id'], '=', $uid)->run();                 
            } else {  
                // Разбанить
                XD::update(['users_banlist'])->set(['banlist_status'], '=', 0)->where(['banlist_user_id'], '=', $uid)->run(); 
                XD::update(['users'])->set(['ban_list'], '=', 0)->where(['id'], '=', $uid)->run();                 
            }
            
        } else {  
          
            // Занесем ip регистрации    
            $user = XD::select('*')->from(['users'])->where(['id'], '=', $uid)->getSelectOne();
            $ip   = $user['reg_ip'];
            
            // Забанить в первый раз
            $date = date("Y-m-d H:i:s");

            XD::insertInto(['users_banlist'], '(', ['banlist_user_id'], ',', ['banlist_ip'], ',', ['banlist_bandate'], ',', ['banlist_int_num'], ',', ['banlist_int_period'], ',', ['banlist_status'], ',', ['banlist_autodelete'], ',', ['banlist_cause'], ')')->values( '(', XD::setList([$uid, $ip, $date, 1, '', 1, 0, '']), ')' )->run();
            
            XD::update(['users'])->set(['ban_list'], '=', 1)->where(['id'], '=', $uid)->run();  
            
        }
        
        return true;   
    }
    
    // Удаленные комментарии
    public static function getCommentsDell() 
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                ->leftJoin(['posts'])->on(['comment_post_id'], '=', ['post_id'])
                ->where(['comment_del'], '=', 1)->orderBy(['comment_id'])->desc();
        
        return  $query->getSelect();
    }
    
    // Восстановление комментария
    public static function CommentsRecover($id)
    {
         XD::update(['comments'])->set(['comment_del'], '=', 0)
        ->where(['comment_id'], '=', $id)->run();
 
        return true;
    }
    
    // Дерева инвайтов
    public static function getInvitations() 
    {
        $q = XD::select(['id', 'login', 'avatar', 'uid', 'active_uid', 'active_time'])->from(['invitation']);
        $query = $q->leftJoin(['users'])->on(['active_uid'], '=', ['id'])->orderBy(['id'])->desc();
        
        return $query->getSelect();
    }
    
    // Просмотр всех пространств в панели администрирования
    public static function getAdminSpaceAll() 
    {
        $q = XD::select('*')->from(['space']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['space_user_id'])
                ->orderBy(['space_id'])->desc();
        
        return  $query->getSelect();
    }
    
    // Все награды
    public static function getBadgesAll()
    {
        return XD::select('*')->from(['badge'])->getSelect();
    }
    
    // Получим информацию по награде
    public static function getBadgeId($badge_id)
    {
       return XD::select('*')->from(['badge'])->where(['badge_id'], '=', $badge_id)->getSelectOne(); 
    }
    
    
    // Редактирование награды
    public static function setEditBadge($data)
    {
        XD::update(['badge'])->set(['badge_title'], '=', $data['badge_title'], ',', 
            ['badge_description'], '=', $data['badge_description'], ',', 
            ['badge_icon'], '=', $data['badge_icon'])
            ->where(['badge_id'], '=', $data['badge_id'])->run(); 

        return true;
    }
    
    // Редактирование награды
    public static function setAddBadge($data)
    {
        XD::insertInto(['badge'], '(', 
            ['badge_title'], ',', 
            ['badge_description'], ',',
            ['badge_icon'], ')')->values( '(', 
        
        XD::setList([
            $data['badge_title'], 
            $data['badge_description'],
            $data['badge_icon']]), ')' )->run();

        return true;
    }
    
}