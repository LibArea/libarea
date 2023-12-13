<?php

use Hleb\Constructor\Handlers\Request;
use App\Models\User\MiddlewareModel;

class UserData
{
    // Banned
    // Забаненный
    const BANNED_USER = 1;

    // Mute mode
    // Немой режим
    const MUTE_MODE_USER = 1;

    // Далее значение поля `trust_level` таблицы `users`
    // Next, the value of the `trust_level' field of the `users` table

    // Unauthorized (level of trust 0)
    // Неавторизированный (уровень доверия 0)
    const USER_ZERO_LEVEL = 0;

    // User (level of trust 1)
    // Пользователь (уровень доверия 1)
    const USER_FIRST_LEVEL = 1;

    // User (level of trust 2)
    // Пользователь (уровень доверия 2)
    const USER_SECOND_LEVEL = 2;

    // User (level of trust 3)
    // Пользователь (уровень доверия 3)
    const USER_THIRD_LEVEL = 3;

    // User (level of trust 4)
    // Пользователь (уровень доверия 4)
    const USER_FOURTH_LEVEL = 4;

    // User (level of trust 5)
    // Пользователь (уровень доверия 5)
    const USER_FIFTH_LEVEL = 5;

    // Administrator (level of trust 10)
    // Администратор (уровень доверия 10)
    const REGISTERED_ADMIN = 10;

    // Administrator ID
    // ID администратора
    const REGISTERED_ADMIN_ID = 1;

    static protected $type = null;

    static protected $myAccount = null;

    static protected $id = null;

    static protected $user = null;


    static public function checkAccordance($type, $compare)
    {
        $check = self::getRegType($type, $compare);
        if (!$check) {
            redirect('/');
        }
        return true;
    }

    /**
     * Returns an array of values if the user is registered
     * Возвращает массив значений, если пользователь зарегистрирован
     * TODO: возможно поменять название...
     */
    static public function get()
    {
        if (self::$user !== null) {
            return self::$user;
        }

        $noAuth = [
            'id'           => self::USER_ZERO_LEVEL,
            'trust_level'  => self::USER_ZERO_LEVEL,
            'scroll'       => self::USER_ZERO_LEVEL,
            'template'     => config('general.template'),
            'lang'         => config('general.lang'),
        ];

        $t = self::getAccount();

        self::$user = $t ?? $noAuth;

        return self::$user;
    }

    static public function getAccount()
    {
        if (self::$myAccount !== null) {
            return self::$myAccount;
        }

        $account = Request::getSession('account') ?? [];

        if (!empty($account['id'])) {

            $user = MiddlewareModel::getUser($account['id']);

            if ($user['ban_list'] == self::BANNED_USER) {
                (new \App\Controllers\Auth\SessionController())->annul($user['id']);
            }
        } else {

            $remember = Request::getCookie('remember');

            if ($remember ?? false) {
                (new \App\Controllers\Auth\RememberController())->check($remember);
            }
        }

        $lang = $user['lang'] ?? config('general.lang');
        Translate::setLang($lang);

        self::$myAccount = $user ?? null;

        return self::$myAccount;
    }

    /**
     * Checking for registration by integer user type and comparison sign.
     * Проверка на регистрацию по числовому типу пользователя и знаку сравнения.
     * @param integer|array $type
     * getRegType(Designator::REGISTERED_ADMIN, '='),
     * getRegType(Designator::USER_ZERO_LEVEL)
     * or getRegType([Designator::BANNED_USER, Designator::MUTE_MODE_USER])
     *
     * @param string|null $cp
     * @return bool
     */
    static public function getRegType($type, $cp = '>='): bool
    {
        $t = self::getUserTl();

        if ((is_integer($type) && (
                ($cp == '=' && $t == $type) ||
                ($cp == '>=' && $t >= $type) ||
                ($cp == '<=' && $t >= $type) ||
                ($cp == '<>' && $t != $type) ||
                ($cp == '!=' && $t != $type) ||
                ($cp == '>' && $t > $type) ||
                ($cp == '<' && $t < $type)
            )) || (is_array($type) && in_array($t, $type))
        ) {
            return true;
        }
        return false;
    }

    /**
     * Checking for administrator and higher.
     * Проверка на администратора и выше.
     * @return bool
     */
    static public function checkAdmin(): bool
    {
        return self::getUserTl() == self::REGISTERED_ADMIN;
    }

    /**
     * Check for any unblocked user.
     * Проверка на любого незаблокированного пользователя.
     * @return bool
     */
    static public function checkActiveUser(): bool
    {
        return self::getUserTl() >= self::USER_FIRST_LEVEL;
    }

    /**
     * Returns the trust level if the user is registere.
     * Возвращает уровень доверия, если пользователь зарегистрирован.
     */
    static public function getUserTl(): int
    {
        $t = self::getAccount();

        return  $t['trust_level'] ?? self::USER_ZERO_LEVEL;
    }

    /**
     * Returns the trust level if the user is registere.
     * Возвращает уровень доверия, если пользователь зарегистрирован.
     */
    static public function getUserId(): int
    {
        return self::$myAccount['id'] ?? self::USER_ZERO_LEVEL;
    }

    /**
     * Returns the member template.
     * Возвращает шаблон участника.
     */
    static public function getUserTheme()
    {
        return self::$myAccount['template'] ?? config('general.template');
    }

    /**
     * Returns the member template.
     * Возвращает шаблон участника.
     */
    static public function getLimitingMode()
    {
        return self::$myAccount['limiting_mode'];
    }

    /**
     * Returns the member's avatar file (default).
     * Возвращает файл аватара участника (по умолчанию дефолтный).
     */
    static public function getUserAvatar()
    {
        return self::$myAccount['avatar'];
    }

    /**
     * Returns the login (nickname) of the participant.
     * Возвращает логин (никнейм) участника.
     */
    static public function getUserLogin()
    {
        return self::$myAccount['login'];
    }

    /**
     * Returns the localization of the participant (otherwise by default)
     * Возвращает локализацию участника (в противном случае по умолчанию).
     */
    static public function getUserLang()
    {
        return self::$myAccount['lang'] ?? config('general.lang');
    }

    /**
     * Returns whether the member has scroll enabled.
     * Возвращает, включен ли скролл у участника.
     */
    static public function getUserScroll()
    {
        return self::$myAccount['scroll'] ?? false;
    }
    
    static public function getUserNSFW()
    {
        return self::$myAccount['nsfw'] ?? false;
    }
	
    static public function getUserPostDesign()
    {
        return self::$myAccount['post_design'] ?? false;
    }
	
    static public function getUserBlog()
    {
        return MiddlewareModel::getBlog(self::getUserId());
    }
}
