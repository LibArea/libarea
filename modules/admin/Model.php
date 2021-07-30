<?php

namespace Modules\Admin;

use Hleb\Constructor\Handlers\Request;
use DB;
use PDO;

class Model extends \MainModel
{
    // Страница участников
    public static function getUsersListForAdmin($page, $limit, $sheet)
    {
        $string = "WHERE ban_list > 0";
        if ($sheet == 'all') 
        {
            $string = "";
        } 
        
        $start  = ($page-1) * $limit;
        $sql = "SELECT 
                    id,
                    login,
                    email,
                    name,
                    avatar,
                    created_at,
                    trust_level,
                    activated,
                    invitation_id,
                    limiting_mode,
                    reg_ip,
                    ban_list
                        FROM users $string ORDER BY id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество участинков
    public static function getUsersListForAdminCount($sheet)
    {
        $string = "WHERE ban_list > 0";
        if ($sheet == 'all') 
        {
            $string = "";
        }
        
        $sql = "SELECT id FROM users $string";

        return DB::run($sql)->rowCount(); 
    }
    
    // По логам
    public static function userLogId($user_id)
    {
        $sql = "SELECT 
                    logs_id,
                    logs_user_id,
                    logs_login,
                    logs_trust_level,
                    logs_ip_address,
                    logs_date
                        FROM users_logs 
                        WHERE logs_user_id = :user_id ORDER BY logs_user_id DESC";
        
        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC); 
    }
    
    // Получение информации по ip для сопоставления
    public static function getUserLogsId($ip)
    {
        $sql = "SELECT 
                    logs_id,
                    logs_user_id,
                    logs_login,
                    logs_trust_level,
                    logs_ip_address,
                    logs_date,
                    id
                        FROM users_logs 
                        LEFT JOIN users ON id = logs_user_id
                        WHERE logs_ip_address = :ip";
        
        return DB::run($sql, ['ip' => $ip])->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Проверка IP на дубликаты
    public static function replayIp($ip)
    {
        $sql = "SELECT 
                    id, 
                    reg_ip 
                        FROM users WHERE reg_ip = :ip";

        return DB::run($sql, ['ip' => $ip])->rowCount();
    }
    
    // Находит ли пользователь в бан- листе и рабанен ли был он
    public static function isBan($user_id)
    {
        $sql = "SELECT 
                    banlist_user_id, 
                    banlist_status,
                    banlist_int_num
                        FROM users_banlist 
                        WHERE banlist_user_id = :user_id AND banlist_status = 1";
        
        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC); 
    }
    
    public static function setBanUser($user_id)
    {
        $sql = "SELECT 
                    banlist_user_id,
                    banlist_status
                        FROM users_banlist WHERE banlist_user_id = :user_id ";
                        
        $sample = DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
        $num    = DB::run($sql, ['user_id' => $user_id])->rowCount();
     
        if ($num != 0) { 
        
            $result = Array();
            foreach ($sample as $row) 
            {
                $status = $row['banlist_status'];
            }  

            if ($status == 0) 
            {   
            	// Забанить повторно
                // Проставляем в banlist_int_num 2, что пока означет: возможно > 2
                $sql = "UPDATE users_banlist
                            SET banlist_int_num = 2, banlist_status = 1
                                WHERE banlist_user_id = :user_id";
        
                DB::run($sql, ['user_id' => $user_id]);
                
                self::setUserBanList($user_id, 1);               
            } 
            else 
            {  
                // Разбанить
                $sql = "UPDATE users_banlist
                            SET banlist_status = 0
                                WHERE banlist_user_id = :user_id";
        
                DB::run($sql, ['user_id' => $user_id]);

                self::setUserBanList($user_id, 0);                
            }
            
        } 
        else 
        {  
            // Занесем ip регистрации    
            $sql = "SELECT 
                        id, 
                        reg_ip
                            FROM users WHERE id = :user_id";
        
            $user = DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC); 
            
            $params = [
                'banlist_user_id'       => $user_id,
                'banlist_ip'            => $user['reg_ip'],
                'banlist_bandate'       => date("Y-m-d H:i:s"),
                'banlist_int_num'       => 1,
                'banlist_int_period'    => '',
                'banlist_status'        => 1,
                'banlist_autodelete'    => 0,
                'banlist_cause'         => '',
            ];

            $sql = "INSERT INTO users_banlist(banlist_user_id, 
                        banlist_ip, 
                        banlist_bandate, 
                        banlist_int_num,
                        banlist_int_period,
                        banlist_status,
                        banlist_autodelete,
                        banlist_cause) 
                            VALUES(:banlist_user_id, 
                                :banlist_ip, 
                                :banlist_bandate, 
                                :banlist_int_num,
                                :banlist_int_period,
                                :banlist_status,
                                :banlist_autodelete,
                                :banlist_cause)";
        
            DB::run($sql,$params); 

            self::setUserBanList($user_id, 1);
        }
        
        return true;   
    }
    
    // Изменим отмеку о занесении в бан-лист
    public static function setUserBanList($user_id, $status) 
    {
       $sql = "UPDATE users 
                    SET ban_list = :status
                        WHERE id = :user_id";
        
        return  DB::run($sql, ['status' => $status, 'user_id' => $user_id]);
    }
    
    // Дерева инвайтов
    public static function getInvitations() 
    {
        $sql = "SELECT 
                    id,
                    login,
                    avatar,
                    uid,
                    active_uid,
                    active_time
                        FROM invitation 
                        LEFT JOIN users ON active_uid = id ORDER BY id DESC";
        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Все награды
    public static function getBadgesAll()
    {
        $sql = "SELECT 
                    badge_id,
                    badge_icon,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_description
                        FROM badge";
        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Получим информацию по награде
    public static function getBadgeId($badge_id)
    {
        $sql = "SELECT 
                    badge_id,
                    badge_icon,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_description
                        FROM badge 
                        WHERE badge_id = :badge_id";
        
        return DB::run($sql, ['badge_id' => $badge_id])->fetch(PDO::FETCH_ASSOC); 
    }
    
    // Редактирование награды
    public static function setEditBadge($data)
    {
        $params = [
            'badge_title'       => $data['badge_title'],
            'badge_description' => $data['badge_description'],
            'badge_icon'        => $data['badge_icon'],
            'badge_id'          => $data['badge_id'],
        ];

        $sql = "UPDATE badge 
                    SET badge_title = :badge_title,  
                    badge_description = :badge_description, 
                    badge_icon = :badge_icon 
                        WHERE badge_id = :badge_id";
        
        return  DB::run($sql, $params);
    }
    
    // Добавить награды
    public static function setAddBadge($data)
    {
        $params = [
            'badge_tl'          => $data['badge_tl'],
            'badge_score'       => $data['badge_score'],
            'badge_title'       => $data['badge_title'],
            'badge_description' => $data['badge_description'],
            'badge_icon'        => $data['badge_icon'],
        ];

        $sql = "INSERT INTO badge(badge_tl, 
                        badge_score, 
                        badge_title, 
                        badge_description, 
                        badge_icon) 
                            VALUES(:badge_tl, 
                                :badge_score, 
                                :badge_title, 
                                :badge_description, 
                                :badge_icon)";
        
        return DB::run($sql,$params); 
    }
    
    // Наградить участника
    public static function badgeUserAdd($user_id, $badge_id)
    {
        $params = [
            'user_id'   => $user_id,
            'badge_id'  => $badge_id,
        ];

        $sql = "INSERT INTO badge_user(bu_user_id, bu_badge_id) 
                    VALUES(:user_id, :badge_id)";
                    
        return DB::run($sql,$params);
      
    }
    
    // Редактирование участника
    public static function setUserEdit($data)
    {
        $params = [
            'id'            => $data['id'],
            'email'         => $data['email'],
            'login'         => $data['login'],
            'name'          => $data['name'],
            'activated'     => $data['activated'],
            'limiting_mode' => $data['limiting_mode'],
            'about'         => $data['about'],
            'trust_level'   => $data['trust_level'],
            'website'       => $data['website'],
            'location'      => $data['location'],
            'public_email'  => $data['public_email'],
            'skype'         => $data['skype'],
            'twitter'       => $data['twitter'],
            'telegram'      => $data['telegram'],
            'vk'            => $data['vk'],
        ];
        
        $sql = "UPDATE users 
                    SET email       = :email,  
                    login           = :login, 
                    name            = :name,
                    activated       = :activated,
                    limiting_mode   = :limiting_mode,
                    about           = :about,
                    trust_level     = :trust_level,
                    website         = :website,
                    location        = :location,
                    public_email    = :public_email,
                    skype           = :skype,
                    twitter         = :twitter,
                    telegram        = :telegram,
                    vk              =:vk
                        WHERE id = :id";
        
        return  DB::run($sql, $params);
    }
    
    // Страница аудита
    public static function getAuditsAll($page, $limit, $sheet)
    {
        $sort = "audit_read_flag = 0";
        if ($sheet == 'approved') 
        {
            $sort = "audit_read_flag = 1";
        }
        
        $start  = ($page-1) * $limit;
        $sql = "SELECT 
                    audit_id,
                    audit_type,
                    audit_data,
                    audit_user_id,
                    audit_content_id,
                    audit_read_flag
                        FROM audits WHERE $sort ORDER BY audit_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public static function getAuditsAllCount($sheet)
    {
        $sort = "audit_read_flag = 0";
        if ($sheet == 'approved') 
        {
            $sort = "audit_read_flag = 1";
        }
        
        $sql = "SELECT id FROM users WHERE $sort";

        return DB::run($sql)->rowCount(); 
    }
    
    // Восстановление
    public static function recoveryAudit($id, $type)
    {
        $sql = "UPDATE ".$type."s SET ".$type."_published = 1 WHERE ".$type."_id = :id";

        DB::run($sql, ['id' => $id]);
        
        self::auditReadFlag($id);
        
        return true;
    }
    
    
    public static function auditReadFlag($id)
    {  
        $sql = "UPDATE audits
                    SET audit_read_flag = 1 
                        WHERE audit_content_id = :id";
        
        return  DB::run($sql, ['id' => $id]);
    }
    
    
    // Пространства открытые / забаненные
    public static function getSpaces($page, $limit, $sort) 
    {
        $signet = "space_is_delete = 0";
        if ($sort == 'ban') 
        { 
            $signet = "space_is_delete = 1"; 
        } 
        
        $start  = ($page-1) * $limit;
        $sql = "SELECT 
                space_id, 
                space_name, 
                space_description,
                space_slug, 
                space_img,
                space_date,
                space_type,
                space_user_id,
                space_is_delete,
                id,
                login,
                avatar
                    FROM space  
                    LEFT JOIN users ON id = space_user_id
                    WHERE $signet
                    ORDER BY space_id DESC LIMIT $start, $limit";

       return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);  
    }

    // Количество
    public static function getSpacesCount($sort)
    {
        $signet = "space_is_delete = 0";
        if ($sort == 'ban') 
        { 
            $signet = "space_is_delete = 1"; 
        } 
        
        $sql = "SELECT space_id, space_is_delete FROM space WHERE $signet";

        return DB::run($sql)->rowCount(); 
    }
    
}