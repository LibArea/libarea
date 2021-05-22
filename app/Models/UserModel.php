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
    public static function getUsersAll($user_id)
    {
        $q = XD::select(['id', 'login', 'name', 'avatar', 'is_deleted'])
            ->from(['users'])
            ->where(['is_deleted'], '=', 0);
                
            if($user_id) {    
                $query = $q->orderBy(['id'], '=', $user_id)->desc(',', ['id'])->desc();
            } else {    
                $query = $q->orderBy(['id'])->desc();
            }
            
        return $query->getSelect();
    }

    // Получение информации по логину
    public static function getUserLogin($login)
    {
        $query = XD::select(['id', 'login', 'name', 'email', 'avatar', 'cover_art', 'color',  'invitation_available', 'about', 'created_at', 'my_post'])
                ->from(['users'])
                ->where(['login'], '=', $login);

        return $query->getSelectOne();
    }
    
    // Получение информации по id
    public static function getUserId($id)
    {
        $query = XD::select(['id', 'login', 'name', 'email', 'avatar', 'invitation_available',  'about', 'trust_level', 'hits_count',  'ban_list'])
                ->from(['users'])
                ->where(['id'], '=', $id);

        return $query->getSelectOne();
    }

    // Регистрация участника
    public static function createUser($login, $email, $password, $reg_ip, $invitation_id)
    {
        // количество участников 
        $count = count(XD::select('*')->from(['users'])->getSelect());
      
        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        if($count < 50 && Config::get(Config::PARAM_MODE) == 1) {
            $trust_level = 1; // Режим "запуска сообщества"
        } else {
            $trust_level = 0; // 0 min, 5 TL max (5 = персонал)
        }

        $password    = password_hash($password, PASSWORD_BCRYPT);
        $activated   = 1; // установить на 0, если e-mail активация будет запущена
                
        XD::insertInto(['users'], '(', ['login'], ',', ['email'], ',', ['password'], ',', ['activated'], ',', ['reg_ip'],',', ['trust_level'],',', ['invitation_id'],  ')')->values( '(', XD::setList([$login, $email, $password, $activated, $reg_ip, $trust_level, $invitation_id]), ')' )->run();
        
       return  XD::select()->last_insert_id('()')->getSelectValue(); // Вернем последний id для таблицы invitation (active_uid)
    }
    
    // Изменение пароля
    public static function editPassword($id, $password)
    {
        return XD::update(['users'])->set(['password'], '=', $password)->where(['id'], '=', $id)->run();
    }

    // Изменение аватарки
    public static function setAvatar($login, $img)
    {
        return XD::update(['users'])->set(['avatar'], '=', $img)->where(['login'], '=', $login)->run();
    }

    // Просмотры  
    public static function userHits($user_id)
    {
        $sql = "UPDATE users SET hits_count = (hits_count + 1) WHERE id = ".$user_id."";
        DB::run($sql); 
    }   

   // Получение аватарки
    public static function getAvatar($login)
    {
        $query = XD::select(['login', 'avatar'])->from(['users'])->where(['login'], '=', $login);

        return $query->getSelectOne();
    }

    // Изменение обложку
    public static function setCover($login, $img)
    {
        return XD::update(['users'])->set(['cover_art'], '=', $img)->where(['login'], '=', $login)->run();
    }
    
   // Получение обложки
    public static function getCover($login)
    {
        $query = XD::select(['login', 'cover_art'])->from(['users'])->where(['login'], '=', $login);

        return $query->getSelectOne();
    }

   // TL - название
    public static function getUserTrust($id)
    {
        $q = XD::select(['id', 'trust_level', 'trust_id', 'trust_name'])->from(['users_trust_level']);
        $query = $q->leftJoin(['users'])->on(['trust_level'], '=', ['trust_id'])
                 ->where(['id'], '=', $id);
                 
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
                ->and(['post_is_delete'], '=', 0)
                ->orderBy(['post_id'])->desc();
  
        return $query->getSelect();
    } 

    // Информация участника
    public static function userInfo($email) 
    {
        $query = XD::select(['id', 'email', 'password', 'login', 'name', 'avatar', 'trust_level', 'ban_list'])
             ->from(['users'])
             ->where(['email'], '=', $email);

        return $query->getSelectOne();
    }
    
    // Количество постов на странице профиля
    public static function userPostsNum($id)
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                 ->where(['id'], '=', $id)->and(['post_draft'], '=', 0);
       
        return count($query->getSelect());
    } 
    
    // Количество ответов на странице профиля
    public static function userAnswersNum($id)
    {
        $q = XD::select('*')->from(['answers']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['answer_user_id'])
                 ->where(['id'], '=', $id);
       
        return count($query->getSelect());
    } 
    
    // Количество комментариев на странице профиля
    public static function userCommentsNum($id)
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                 ->where(['id'], '=', $id);
       
        return count($query->getSelect());
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
    public static function editProfile($login, $name, $about, $color)
    {
        XD::update(['users'])->set(['name'], '=', $name, ',', ['about'], '=', $about, ',', ['color'], '=', $color)->where(['login'], '=', $login)->run();
 
        return true;
    }
    
    // Удалим обложку для профиля
    public static function userCoverRemove($user_id)
    {
        return XD::update(['users'])->set(['cover_art'], '=', 'cover_art.jpeg')
                ->where(['id'], '=', $user_id)->run();
    }
    
    // Записываем последние данные авторизации
    public static function setUserLastLogs($id, $login, $trust_level, $last_ip) 
    {
        XD::insertInto(['users_logs'], '(', ['logs_user_id'], ',', ['logs_login'], ',', ['logs_trust_level'], ',', ['logs_ip_address'], ')')->values( '(', XD::setList([$id, $login, $trust_level, $last_ip]), ')' )->run();
        return true;   
    }
    
    // Находит ли пользователь в бан- листе
    public static function isBan($uid)
    {
        
        $result = XD::select('*')->from(['users_banlist'])
                ->where(['banlist_user_id'], '=', $uid)
                ->and(['banlist_status'], '=', 1)->getSelectOne();

        return $result;   
    }
    
    // Активирован ли пользователь (e-mail)
    public static function isActivated($uid)
    {
        
        $result = XD::select('*')->from(['users'])
                ->where(['id'], '=', $uid)
                ->and(['activated'], '=', 1)->getSelectOne();

        return $result;   
    }
    
    ////// ЗАПОМНИТЬ МЕНЯ
    ////// Работа с токенами и куки (перенести в BASE)
    
    // Проверяет, устанавливался ли когда-либо файл cookie «запомнить меня»
    // Если мы найдем, проверьте его по нашей таблице users_auth_tokens и  
    // если мы найдем совпадение, и оно все ещё в силе.
    public static function checkCookie()
    {
        // Есть "remember" куки?
        $remember = Request::getCookie('remember');

        // Нет
        if (empty($remember)) {
            return;
        }

        // Получим наш селектор | значение валидатора
        [$selector, $validator] = explode(':', $remember);
        $validator = hash('sha256', $validator);

        $token = self::getAuthTokenBySelector($selector);
 
        if (empty($token)) {

            return false;
        }

        // Хэш не соответствует
        if (!hash_equals($token['auth_hashedvalidator'], $validator)) {

            return false;
        }
 
        // Получение данных по id
        $user = self::getUserId($token['auth_user_id']);

        // Нет пользователя
        if (empty($user)) {

            return false;
        }

        // ПРОСТО ПЕРЕД УСТАНОВКОЙ ДАННЫХ СЕССИИ И ВХОДОМ ПОЛЬЗОВАТЕЛЯ
        // ДАВАЙТЕ ПРОВЕРИМ, НУЖЕН ЛИ ИХ ПРИНУДИТЕЛЬНЫЙ ВХОД
        // Перенесем в конфиг?
        $forceLogin = 0;
        if ($forceLogin > 1) {

            // ПОЛУЧАЕТ СЛУЧАЙНОЕ ЧИСЛО ОТ 1 до 100
            // ЕСЛИ ЭТО НОМЕР МЕНЬШЕ ЧЕМ НОМЕР В НАСТРОЙКАХ ПРИНУДИТЕЛЬНОГО ВХОДА
            // УДАЛИТЬ ТОКЕН ИЗ БД

            if (rand(1, 100) < $forceLogin) {

                self::DeleteTokenByUserId($token['auth_user_id']);               

                return;
            }
        }

        // Сессия участника
        self::setUserSession($user, '1');

        $uid = $token['auth_user_id'];

        self::rememberMeReset($uid, $selector);

        return;
    }

    public static function setUserSession($user)
    {   
        $data = [
            'user_id'       => $user['id'],
            'login'         => $user['login'],
            'name'          => $user['name'],
            'email'         => $user['email'],
            'trust_level'   => $user['trust_level'],
            'about'         => $user['about'],
            'avatar'        => $user['avatar'],
            'isLoggedIn'    => true,
            'ipaddress'     => Request::getRemoteAddress(),
        ];

       
        $_SESSION['account'] = $data;
        //redirect('/');

        return true;
    }

    public static function rememberMe($user_id)
    {
        // НАСТРОЕМ НАШ СЕЛЕКТОР, ВАЛИДАТОР И СРОК ДЕЙСТВИЯ 
        // Селектор действует как уникальный идентификатор, поэтому нам не нужно 
        // сохранять идентификатор пользователя в нашем файле cookie
        // валидатор сохраняется в виде обычного текста в файле cookie, но хэшируется в бд
        // если селектор (id) найден в таблице auth_tokens, мы затем сопоставляем валидаторы

        $rememberMeExpire = 30;
        $selector = Base::randomString('crypto', 12);
        $validator = Base::randomString('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установим токен
        $token = $selector . ':' . $validator;

        // Массив данных
        $data = [
            'user_id' => $user_id,
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
        $result = self::getAuthTokenByUserId($user_id);

        // Если не вставить
        if (empty($result)) {
            self::insertToken($data);
        } 
        // Если есть обновление
        else {
            self::updateToken($data, $user_id);
        }
        
        // set_Cookie
        setcookie("remember", $token, $expires);
          //  '',     // cookieDomain
          //  '/',    // cookiePath
          //  false,  // cookieSecure
          //  false,  // cookieHTTPOnly
        
    } 

    // Каждый раз, когда пользователь входит в систему, используя свой файл cookie «запомнить меня»
    // Сбросить валидатор и обновить БД
    public static function rememberMeReset($uid, $selector)
    {
        // Получаем по селектору       
        $existingToken = self::getAuthTokenBySelector($selector);

        if (empty($existingToken)) {

            return self::rememberMe($uid);
        }

        $rememberMeExpire = 30;
        $validator = Base::randomString('crypto', 20);
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
                'expires' => date('Y-m-d H:i:s', $expires)
            ];
        } else {
            $data = [
                'hashedvalidator' => hash('sha256', $validator),
            ];
        }

        self::UpdateSelector($data, $selector);

        setcookie("remember", $token, $expires);
           // '',     // cookieDomain
           // '/',    // cookiePath
           // false,  // cookieSecure
           // false,  // cookieHTTPOnly
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
        return  XD::insertInto(['users_auth_tokens'], '(', ['auth_user_id'], ',', ['auth_selector'], ',', ['auth_hashedvalidator'], ',', ['auth_expires'], ')')->values( '(', XD::setList([$data['user_id'], $data['selector'], $data['hashedvalidator'], $data['expires']]), ')' )->run();
    }
    
    public static function updateToken($data, $uid)
    {
        return  XD::update(['users_auth_tokens'])->set(['auth_user_id'], '=', $data['user_id'], ',', ['auth_selector'], '=', $data['selector'], ',', ['auth_hashedvalidator'], '=', $data['hashedvalidator'], ',', ['auth_expires'], '=', $data['expires'])->where(['auth_user_id'], '=', $uid)->run();
    }
    
    public static function DeleteTokenByUserId($uid)
    {
        return XD::deleteFrom(['users_auth_tokens'])->where(['auth_user_id'], '=', $uid)->run(); 
    }
    
    public static function UpdateSelector($data, $selector)
    {
       return  XD::update(['users_auth_tokens'])->set(['auth_hashedvalidator'], '=', $data['hashedvalidator'], ',', ['auth_expires'], '=', $data['expires'])->where(['auth_selector'], '=', $selector)->run();
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
    
    // Получаем токен аутентификации по селектору
    public static function getAuthTokenBySelector($selector)
    {
        return XD::select('*')->from(['users_auth_tokens'])->where(['auth_selector'], '=', $selector)->getSelectOne();
    }

    // Проверяем код смены пароля (использовали его или нет)
    public static function getPasswordActivate($code)
    {
        return XD::select('*')->from(['users_activate'])
                ->where(['activate_code'], '=', $code)
                ->and(['activate_flag'], '!=', 1)->getSelectOne();
    }
 
    // Создадим инвайт для участника
	public static function addInvitation($uid, $invitation_code, $invitation_email, $add_time, $add_ip)
	{
        $inv        = XD::select('*')->from(['users'])->where(['id'], '=', $uid)->getSelectOne();
        $num_inv    = $inv['invitation_available']; // получаем количество инвайтов 
        $num        = $num_inv + 1;          // плюсуем один

        XD::update(['users'])->set(['invitation_available'], '=', $num)->where(['id'], '=', $uid,)->run();

        return  XD::insertInto(['invitation'], '(', ['uid'], ',', ['invitation_code'], ',', ['invitation_email'], ',', ['add_time'], ',', ['add_ip'], ')')->values( '(', XD::setList([$uid, $invitation_code, $invitation_email, $add_time, $add_ip]), ')' )->run();
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
    public static function EmailActivate($user_id){
       
        XD::update(['users_email_activate'])->set(['email_activate_flag'], '=', 1)
                ->where(['user_id'], '=', $user_id)->run();
        
        XD::update(['users'])->set(['activated'], '=', 1)
                ->where(['id'], '=', $user_id)->run();        
       
        return true;
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
