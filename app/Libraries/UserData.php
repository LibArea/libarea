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

    static public function getAccount()
    {
        if (!is_null(self::$myAccount)) {
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
            if ($remember) {
                (new \App\Controllers\Auth\RememberController())->check($remember);
            }
        }

        $lang = $user['lang'] ?? Config::get('general.lang');
        Translate::setLang($lang);

        self::$myAccount = $user ?? null;

        return self::$myAccount;
    }

    /**
     * Returns the trust level if the user is registere.
     * Возвращает уровень доверия, если пользователь зарегистрирован.
     */
    static public function getTl()
    {
        $t = self::getAccount();

        return $t['trust_level'] ?? false;
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
        $t = self::getTl();
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
     * Checking for a user who is marked as deleted or banned.
     * Проверка на пользователя, который помечен удалённым или забанен.
     * @return bool
     */
    static public function checkBan(): bool
    {
        return self::getTl() < self::USER_ZERO_LEVEL;
    }

    /**
     * Check for any unblocked user.
     * Проверка на любого незаблокированного пользователя.
     * @return bool
     */
    static public function checkActiveUser(): bool
    {
        return self::getTl() >= self::USER_FIRST_LEVEL;
    }

    /**
     * Returns an array of values if the user is registered
     * Возвращает массив значений, если пользователь зарегистрирован
     * TODO: возможно поменять название...
     */
    static public function get()
    {
        if (!is_null(self::$user)) {
            return self::$user;
        }

        $noAuth = [
            'id'           => self::USER_ZERO_LEVEL,
            'trust_level'  => self::USER_ZERO_LEVEL,
            'scroll'       => self::USER_ZERO_LEVEL,
            'template'     => Config::get('general.template'),
            'lang'         => Config::get('general.lang'),
        ];

        $t = self::getAccount();

        self::$user = $t ? $t : $noAuth;

        return self::$user;
    }

    /**
     * Возвращает ID, если пользователь зарегистрирован
     * @return null|int
     */
    static public function getUserId(): ?int
    {
        if (!is_null(self::$id)) {
            return self::$id;
        }

        $t = self::getAccount();

        self::$id = $t['id'] ?? 0;

        return self::$id;
    }

    /**
     * Checking for administrator and higher.
     * Проверка на администратора и выше.
     * @return bool
     */
    static public function checkAdmin(): bool
    {
        return self::getTl() == self::REGISTERED_ADMIN;
    }
}
