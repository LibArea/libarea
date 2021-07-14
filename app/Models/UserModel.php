<?php

namespace App\Models;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;
use Lori\Base;
use Lori\Config;
use DB;
use PDO;

class UserModel extends \MainModel
{
    // Страница участников
    public static function getUsersAll($page, $limit, $user_id)
    {  
        $start  = ($page-1) * $limit;
        $sql = "SELECT  
                    id,
                    login,
                    name, 
                    avatar,
                    is_deleted
                    
                        FROM users 
                        WHERE is_deleted != 1 and ban_list != 1
                        ORDER BY id = :user_id DESC, trust_level DESC LIMIT $start, $limit"; 
        
        return DB::run($sql, ['user_id' =>$user_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Количество
    public static function getUsersAllCount()
    {
        $sql = "SELECT 
                    id, 
                    is_deleted
                    
                    FROM users
                    WHERE is_deleted = 0";

        return  DB::run($sql)->rowCount(); 
    }

    // Информация по участнику (id, slug)
    public static function getUser($params, $name)
    {
        $sort = "id = :params";
        if ($name == 'slug') {
            $sort = "login = :params";
        } 
        
        $sql = "SELECT 
                    id,
                    login,
                    name,
                    activated,
                    reg_ip,
                    email,
                    avatar,
                    trust_level,
                    cover_art,
                    color,
                    invitation_available,
                    about,
                    website,
                    location,
                    public_email,
                    skype,
                    twitter,
                    telegram,
                    vk,
                    created_at,
                    my_post,
                    ban_list,
                    hits_count,
                    is_deleted 
        
                    FROM users WHERE $sort";
        
        $result = DB::run($sql, ['params' => $params]);
        
        return $result->fetch(PDO::FETCH_ASSOC);     
    }
    
    // Select user
    public static function getSearchUsers($query)
    {
        $sql = "SELECT id, login FROM users WHERE login LIKE :login";
        
        $result = DB::run($sql, ['login' => $query."%"]);
        $usersList  = $result->fetchall(PDO::FETCH_ASSOC);

        $response = array();
        foreach ($usersList as $user) {
           $response[] = array(
              "id" => $user['id'],
              "text" => $user['login']
           );
        }

        echo json_encode($response);
    }

    // Регистрация участника
    public static function createUser($login, $email, $password, $reg_ip, $invitation_id)
    {
        // количество участников 
        $count = count(XD::select('*')->from(['users'])->getSelect());
      
        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        if ($count < 50 && Config::get(Config::PARAM_MODE) == 1) {
            $trust_level = 1; // Режим "запуска сообщества"
        } else {
            $trust_level = 0; // 0 min, 5 TL max (5 = персонал)
        }

        $password    = password_hash($password, PASSWORD_BCRYPT);

        // 0 - требуется активация по e-mail
        $activated   = 0;
                
        XD::insertInto(['users'], '(', ['login'], ',', ['email'], ',', ['password'], ',', ['activated'], ',', ['reg_ip'],',', ['trust_level'],',', ['invitation_id'],  ')')->values( '(', XD::setList([$login, $email, $password, $activated, $reg_ip, $trust_level, $invitation_id]), ')' )->run();
        
       return  XD::select()->last_insert_id('()')->getSelectValue(); 
    }
    
    // Изменение пароля
    public static function editPassword($user_id, $password)
    {
        return XD::update(['users'])->set(['password'], '=', $password)->where(['id'], '=', $user_id)->run();
    }

    // Просмотры  
    public static function userHits($user_id)
    {
        $sql = "UPDATE users SET hits_count = (hits_count + 1) WHERE id = :user_id";
        
        return  DB::run($sql,['user_id' => $user_id]); 
    }   

    // Изменение аватарки / обложки
    public static function setImg($user_id, $img)
    {
        return XD::update(['users'])->set(['avatar'], '=', $img)->where(['id'], '=', $user_id)->run();
    }

    public static function setCover($user_id, $img)
    {
        return XD::update(['users'])->set(['cover_art'], '=', $img)->where(['id'], '=', $user_id)->run();
    }
    
    // TL - название
    public static function getUserTrust($user_id)
    {
        $q = XD::select(['id', 'trust_level', 'trust_id', 'trust_name'])->from(['users_trust_level']);
        $query = $q->leftJoin(['users'])->on(['trust_level'], '=', ['trust_id'])
                 ->where(['id'], '=', $user_id);
                 
        return $query->getSelectOne();
    }  

    // Страница закладок участника (комментарии и посты)
    public static function userFavorite($uid)
    {
        $sql = "SELECT favorite.*, 
                       users.id, users.login, users.avatar, 
                       posts.*, 
                       answers.*, 
                       space.*
                fROM favorite
                LEFT JOIN users ON users.id = favorite.favorite_uid
                LEFT JOIN posts ON posts.post_id = favorite.favorite_tid AND favorite.favorite_type = 1
                LEFT JOIN answers ON answers.answer_id = favorite.favorite_tid AND favorite.favorite_type = 2
                LEFT JOIN space ON  space.space_id = posts.post_space_id
                WHERE favorite.favorite_uid = $uid LIMIT 25 "; 
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    } 

    // Страница черновиков
    public static function userDraftPosts($user_id)
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->where(['id'], '=', $user_id)
                ->and(['post_draft'], '=', 1)
                ->and(['post_is_deleted'], '=', 0)
                ->orderBy(['post_id'])->desc();
  
        return $query->getSelect();
    } 

    // Информация участника
    public static function userInfo($email) 
    {
        $query = XD::select(['id', 'email', 'password', 'login', 'name', 'avatar', 'trust_level', 'ban_list'])
                 ->from(['users'])->where(['email'], '=', $email);

        return $query->getSelectOne();
    }
    
    // Количество контента участника
    public static function contentCount($user_id, $type)   
    {
        if ($type == 'posts') {
            
            $sql = "SELECT post_id, post_draft, post_is_deleted 
                    FROM posts WHERE post_user_id = :user_id and post_draft = 0 and post_is_deleted = 0";
            
        } elseif ($type == 'comments') {
            $sql = "SELECT comment_id FROM comments WHERE comment_user_id = :user_id and comment_is_deleted = 0";
        } else {
            $sql = "SELECT answer_id FROM answers WHERE answer_user_id = :user_id and answer_is_deleted = 0";
        }

        return  DB::run($sql, ['user_id' => $user_id])->rowCount(); 
    }
    
    // Редактирование профиля
    public static function editProfile($data)
    {
        XD::update(['users'])->set(['name'], '=', $data['name'], ',', 
            ['color'], '=', $data['color'], ',', 
            ['about'], '=', $data['about'], ',', 
            ['website'], '=', $data['website'], ',', 
            ['location'], '=', $data['location'], ',', 
            ['public_email'], '=', $data['public_email'], ',', 
            ['skype'], '=', $data['skype'], ',', 
            ['twitter'], '=', $data['twitter'], ',', 
            ['telegram'], '=', $data['telegram'], ',', 
            ['vk'], '=', $data['vk'])->where(['id'], '=', $data['id'])->run();
        return true;
    }
    
    // Удалим обложку для профиля
    public static function userCoverRemove($user_id)
    {
        return XD::update(['users'])->set(['cover_art'], '=', 'cover_art.jpeg')
                ->where(['id'], '=', $user_id)->run();
    }
    
    // Записываем последние данные авторизации
    public static function setUserLastLogs($user_id, $login, $trust_level, $last_ip) 
    {
        XD::insertInto(['users_logs'], '(', ['logs_user_id'], ',', ['logs_login'], ',', ['logs_trust_level'], ',', ['logs_ip_address'], ')')->values( '(', XD::setList([$user_id, $login, $trust_level, $last_ip]), ')' )->run();
        return true;   
    }
    
    // Находит ли пользователь в бан- листе
    public static function isBan($uid)
    {
        return  XD::select('*')->from(['users_banlist'])
                ->where(['banlist_user_id'], '=', $uid)
                ->and(['banlist_status'], '=', 1)->getSelectOne();
    }
    
    // Активирован ли пользователь (e-mail)
    public static function isActivated($uid)
    {
        return  XD::select('*')->from(['users'])
                ->where(['id'], '=', $uid)
                ->and(['activated'], '=', 1)->getSelectOne();
    }
    
    // Восстановления пароля
    public static function initRecover($uid, $code) 
    {
        $date = date('Y-m-d H:i:s');
                
        return  XD::insertInto(['users_activate'], '(', ['activate_date'], ',', ['activate_user_id'], ',', ['activate_code'], ')')->values( '(', XD::setList([$date, $uid, $code]), ')' )->run();
    }

    // Для одноразового использования кода восстановления
    public static function editRecoverFlag($user_id) 
    {
        return XD::update(['users_activate'])->set(['activate_flag'], '=', 1)->where(['activate_user_id'], '=', $user_id)->run();
    }
    
    // Проверяем код смены пароля (использовали его или нет)
    public static function getPasswordActivate($code)
    {
        return XD::select('*')->from(['users_activate'])
                ->where(['activate_code'], '=', $code)
                ->and(['activate_flag'], '!=', 1)->getSelectOne();
    }
 
    // Создадим инвайт для участника
	public static function addInvitation($user_id, $invitation_code, $invitation_email, $add_time, $add_ip)
	{
        $sql = "UPDATE users SET invitation_available = (invitation_available + 1) WHERE id = :user_id";
        
        DB::run($sql,['user_id' => $user_id]); 

        return  XD::insertInto(['invitation'], '(', ['uid'], ',', ['invitation_code'], ',', ['invitation_email'], ',', ['add_time'], ',', ['add_ip'], ')')->values( '(', XD::setList([$user_id, $invitation_code, $invitation_email, $add_time, $add_ip]), ')' )->run();
	}
    
    // Проверим на повтор
	public static function InvitationOne($uid)
	{
        return XD::select('*')->from(['invitation'])->where(['uid'], '=', $uid)->getSelectOne();
	} 
    
    // Все инвайты участинка
    public static function InvitationResult($uid) 
    {
        $q      = XD::select('*')->from(['invitation']);
        $query  = $q->leftJoin(['users'])->on(['id'], '=', ['active_uid'])
                  ->where(['uid'], '=', $uid)->getSelect();
                  
        return $query;
    }
    
    // Проверим не активированный инвайт
    public static function InvitationAvailable($invitation_code)
	{
        return XD::select('*')->from(['invitation'])
                ->where(['invitation_code'], '=', $invitation_code)
                ->and(['active_status'], '=', 0)
                ->getSelectOne();
	}
    
    // Проверим не активированный инвайт и поменяем статус
    public static function sendInvitationEmail($inv_code, $inv_uid, $reg_ip, $active_uid)
	{
        $active_time = date('Y-m-d H:i:s');

        XD::update(['invitation'])->set(['active_status'], '=', 1, ',', ['active_ip'], '=', $reg_ip, ',', ['active_time'], '=', $active_time, ',', ['active_uid'], '=', $active_uid)
        ->where(['invitation_code'], '=', $inv_code)
        ->and(['uid'], '=', $inv_uid)
        ->run();
		
		return true;
	}
    
    // Делаем запись в таблицу активации e-mail
    public static function sendActivateEmail($user_id, $email_code)
	{
        $pubdate    = date("Y-m-d H:i:s");
  
		XD::insertInto(['users_email_activate'], '(', ['pubdate'], ',', ['user_id'], ',', ['email_code'], ')')->values( '(', XD::setList([$pubdate, $user_id, $email_code]), ')' )->run();
        
		return true;
	} 
    
    // Проверяем код активации e-mail
    public static function getEmailActivate($code)
    {
        return XD::select('*')->from(['users_email_activate'])
                ->where(['email_code'], '=', $code)
                ->and(['email_activate_flag'], '!=', 1)->getSelectOne();
    }
    
    // Активируем e-mail
    public static function EmailActivate($user_id)
    {
        XD::update(['users_email_activate'])->set(['email_activate_flag'], '=', 1)
                ->where(['user_id'], '=', $user_id)->run();
        
        XD::update(['users'])->set(['activated'], '=', 1)
                ->where(['id'], '=', $user_id)->run();        
       
        return true;
    }
    
    // Все награды участника
    public static function getBadgeUserAll($user_id)
    {
        $query  = XD::select('*')->from(['badge_user']);
        $result = $query->leftJoin(['badge'])->on(['badge_id'], '=', ['bu_badge_id'])
                  ->where(['bu_user_id'], '=', $user_id)->getSelect();
        
        return $result;
    }
    
    // Настройка оповещений
    public static function getNotificationSettingByUid($uid)
    {
        return true;
    }  

}
