<?php

namespace App\Models;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class UserModel extends \MainModel
{
    
    // Страница участников
    public static function getUsersAll()
    {

       $query = XD::select(['id', 'login', 'name', 'avatar', 'deleted'])
              ->from(['users'])
              ->where(['deleted'], '=', 0)
              ->orderBy(['id'])->desc();

        $result = $query->getSelect();
        return $result;

    }

    // Получение информации по логину
    public static function getUserLogin($login)
    {

        $query = XD::select(['id', 'login', 'name', 'email', 'avatar', 'about', 'created_at', 'my_post'])
                ->from(['users'])
                ->where(['login'], '=', $login);

        $result = $query->getSelectOne();

        return $result;

    }
    
    
    // Получение информации по id
    public static function getUserId($id)
    {

        $query = XD::select(['id', 'login', 'name', 'email', 'avatar', 'about', 'created_at'])
                ->from(['users'])
                ->where(['id'], '=', $id);

        $result = $query->getSelectOne();

        return $result;

    }

    // Регистрация участника
    public static function createUser($login,$email,$password,$reg_ip)
    {

        // количество участников 
        $count = count(XD::select('*')->from(['users'])->getSelect());
      
        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        if($count < 50 && $GLOBALS['conf']['bootstrap_mode'] == 1) {
            $trust_level = 1; // Режим "запуска сообщества"
        } else {
            $trust_level = 0; // 0 min, 5 TL max (5 = персонал, админ)
        }

        $password    = password_hash($password, PASSWORD_BCRYPT);
        $activated   = 1; // ввести почту и инвайт 
                
        XD::insertInto(['users'], '(', ['login'], ',', ['email'], ',', ['password'], ',', ['activated'], ',', ['reg_ip'],',', ['trust_level'], ')')->values( '(', XD::setList([$login, $email, $password, $activated, $reg_ip, $trust_level]), ')' )->run();
        
        return true;
        
    }
    
    // Изменение пароля
    public static function editPassword($login, $password)
    {
        XD::update(['users'])->set(['password'], '=', $password)->where(['login'], '=', $login)->run();
        return true;
    }

    // Изменение аватарки
    public static function setAvatar($login, $img)
    {
        XD::update(['users'])->set(['avatar'], '=', $img)->where(['login'], '=', $login)->run();
        return true;
    }

   // Получение аватарки
    public static function getAvatar($login)
    {
        $query = XD::select(['login', 'avatar'])->from(['users'])->where(['login'], '=', $login);

        $result = $query->getSelectOne();
        return $result;
    }

   // TL - название
    public static function getUserTrust($id)
    {
        $q = XD::select(['id', 'trust_level', 'trust_id', 'trust_name'])->from(['users_trust_level']);
        $query = $q->leftJoin(['users'])->on(['trust_level'], '=', ['trust_id'])
                 ->where(['id'], '=', $id);
                 
        $result = $query->getSelectOne();
        
        return $result;
    }  

    // Страница постов участника
    public static function getUserFavorite($uid)
    {
         
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->leftJoin(['favorite'])->on(['favorite_tid'], '=', ['post_id'])
                ->where(['favorite_uid'], '=', $uid)
                ->orderBy(['post_id'])->desc();
  
        $result = $query->getSelect();

        return $result;
    } 

    // Информация участника
    public static function getUserInfo($data) 
    {

        $query = XD::select(['id', 'email', 'password', 'login', 'name', 'avatar', 'trust_level'])
             ->from(['users'])
             ->where(['email'], '=', $data);

        $result = $query->getSelectOne();
        return $result;

    }
    
   // Количество постов на странице профиля
    public static function getUsersPostsNum($id)
    {
       
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                 ->where(['id'], '=', $id);
       
        $result = count($query->getSelect());

        return $result;
        
    } 
    
    // Количество комментариев на странице профиля
    public static function getUsersCommentsNum($id)
    {
        
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                 ->where(['id'], '=', $id);
       
        $result = count($query->getSelect());
        return $result;
        
    }
    
    // Количество закладок на странице профиля
    public static function getUsersFavoriteNum($id)
    {
        
        $query = XD::select('*')->from(['favorite'])->where(['favorite_uid'], '=', $id);

        $result = count($query->getSelect());
        return $result;
        
    }
    
    // Проверка Логина на дубликаты
    public static function replayLogin($login)
    {

        $q = XD::select('*')->from(['users']);
        $query = $q->where(['login'], '=', $login);
        $result = $query->getSelectOne();
        
        if ($result) {
            return false;
        }
        
        return true;
        
    }
    
    // Проверка Email на дубликаты
    public static function replayEmail($email)
    {

        $q = XD::select('*')->from(['users']);
        $query = $q->where(['email'], '=', $email);
        $result = $query->getSelectOne();
        
        if ($result) {
            return false;
        }
        
        return true;
        
    }
    
    // Редактирование профиля
    public static function editProfile($login, $name, $about)
    {

        XD::update(['users'])->set(['name'], '=', $name, ',', ['about'], '=', $about)->where(['login'], '=', $login)->run();
 
        return true;
        
    }
    
    // Записываем последние данные авторизации
    public static function setUserLastLogs($id, $login, $trust_level, $last_ip) 
    {
         
        XD::insertInto(['users_logs'], '(', ['logs_user_id'], ',', ['logs_login'], ',', ['logs_trust_level'], ',', ['logs_ip_address'], ')')->values( '(', XD::setList([$id, $login, $trust_level, $last_ip]), ')' )->run();
        return true;   
    }
    
    // Персонал
    public static function isAdmin($uid) 
    {
        
        $result = XD::select(['id', 'trust_level'])->from(['users'])->where(['id'], '=', $uid)->getSelectOne();

        if($result['trust_level'] != 5) {
            return false;    
        }

        return true;   
        
    }
    
    // Находит ли пользователь в бан- листе
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
    
    ////// ЗАПОМНИТЬ МЕНЯ
    ////// Работа с токенамм
 
    public function rememberMe($uid)
    {

        // НАСТРОЕМ НАШ СЕЛЕКТОР, ВАЛИДАТОР И СРОК ДЕЙСТВИЯ 
        // Селектор действует как уникальный идентификатор, поэтому нам не нужно 
        // сохранять идентификатор пользователя в нашем файле cookie
        // валидатор сохраняется в виде обычного текста в файле cookie, но хэшируется в бд
        // если селектор (id) найден в таблице auth_tokens, мы затем сопоставляем валидаторы

        $rememberMeExpire = 30;
        $selector = random_string('crypto', 12);
        $validator = random_string('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установим токен
        $token = $selector . ':' . $validator;

        // Массив данных
        $data = [
            'user_id' => $uid,
            'selector' => $selector,
            'hashedvalidator' => hash('sha256', $validator),
            'expires' => date('Y-m-d H:i:s', $expires),
        ];        


        // ПРОВЕРИМ, ЕСТЬ ЛИ У ИДЕНТИФИКАТОРА ПОЛЬЗОВАТЕЛЯ УЖЕ НАБОР ТОКЕНОВ
        // Мы действительно не хотим иметь несколько токенов и селекторов для
        // одного и того же идентификатора пользователя. В этом нет необходимости, 
        // так как валидатор обновляется при каждом входе в систему
        // поэтому проверим, есть ли уже маркер, и перепишем, если он есть.
        // Следует немного снизить уровень обслуживания БД и устранить необходимость в спорадических чистках.
        $result = self::GetAuthTokenByUserId($uid);

        // Если не вставить
        if (empty($result)) {
            self::insertToken($data);
        } 
        // Если есть обновление
        else {
            self::updateToken($data, $uid);
        }

        // set_Cookie
        setcookie(
            "remember",
            $token,
            $expires,
            $GLOBALS['conf']['cookiePath'],
            $GLOBALS['conf']['cookieDomain'],
            $GLOBALS['conf']['cookieSecure'],
            $GLOBALS['conf']['cookieHTTPOnly'],
        );
    } 

    // Каждый раз, когда пользователь входит в систему, используя свой файл cookie «запомнить меня»
    // Сбросить валидатор и обновить БД
    public static function rememberMeReset($uid, $selector)
    {
        // Получаем по селектору       
        $existingToken = self::GetAuthTokenBySelector($selector);

        if (empty($existingToken)) {

            return $this->rememberMe($uid);
        }

        $rememberMeExpire = 30;
        $validator = random_string('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установить
        $token = $selector . ':' . $validator;


        // Если установлено значение true, каждый раз, когда пользователь посещает сайт 
        // и обнаруживает файл cookie новая дата истечения срока действия 
        // устанавливается с помощью параметра  $rememberMeExpire - выше 
        $rememberMeRenew = true;

        if ($rememberMeRenew) {
            // Массивы данных установим
            $data = [
                'hashedvalidator' => hash('sha256', $validator),
                'expires' => date('Y-m-d H:i:s', $expires),
            ];
        } else {
            $data = [
                'hashedvalidator' => hash('sha256', $validator),
            ];
        }

        self::UpdateSelector($data, $selector);

        setcookie(
            "remember",
            $token,
            $expires,
            $GLOBALS['conf']['cookiePath'],
            $GLOBALS['conf']['cookieDomain'],
            $GLOBALS['conf']['cookieSecure'],
            $GLOBALS['conf']['cookieHTTPOnly'],
        );
    }



    // Поля в таблице users_auth_tokens
    // auth_id,	auth_user_id,	auth_selector,	auth_hashedvalidator,	auth_expires	
    public static function getAuthTokenByUserId($uid)
    {

        return XD::select('*')->from(['users_auth_tokens'])
                ->where(['auth_user_id'], '=', $uid)->getSelectOne();

    }
 
    public static function insertToken($data)
    {
        
        XD::insertInto(['users_auth_tokens'], '(', ['auth_user_id'], ',', ['auth_selector'], ',', ['auth_hashedvalidator'], ',', ['auth_expires'], ')')->values( '(', XD::setList([$data['user_id'], $data['selector'], $data['hashedvalidator'], $data['expires']]), ')' )->run();
        
        return true;
    }
    
    public static function updateToken($data, $uid)
    {
        
        XD::update(['users_auth_tokens'])->set(['auth_user_id'], '=', $data['user_id'], ',', ['auth_selector'], '=', $data['selector'], ',', ['auth_hashedvalidator'], '=', $data['hashedvalidator'], ',', ['auth_expires'], '=', $data['expires'])->where(['auth_user_id'], '=', $uid)->run();
        
        return true;
    }
    
    public function DeleteTokenByUserId($uid)
    {
        
        XD::deleteFrom(['users_auth_tokens'])->where(['auth_user_id'], '=', $uid)->run(); 
        
        return true;
    }
    
    public function UpdateSelector($data, $selector)
    {
 
       XD::update(['users_auth_tokens'])->set(['auth_user_id'], '=', $data['user_id'], ',', ['auth_selector'], '=', $data['selector'], ',', ['auth_hashedvalidator'], '=', $data['hashedvalidator'], ',', ['auth_expires'], '=', $data['expires'])->where(['auth_selector'], '=', $data['selector'])->run();
       
       return true;
        
    }
 
    
    // Получаем токен аутентификации по селектору
    public static function getAuthTokenBySelector($selector)
    {
        
        return XD::select('*')->from(['users_auth_tokens'])
                ->where(['selector'], '=', $selector)->getSelectOne();

    }
    
    // Настройка оповещений
    public static function getNotificationSettingByUid($uid)
    {
        return true;
    }  
    
    // Прочитан или нет
    public static function updateNotificationUnread($uid)
    {
        return true;
    }
    
}
