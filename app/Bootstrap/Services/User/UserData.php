<?php

declare(strict_types=1);

namespace App\Bootstrap\Services\User;

use Hleb\Static\Session;
use Hleb\Static\Cookies;

use App\Bootstrap\Services\Auth\RegType;
use App\Bootstrap\Services\Auth\Action;
use App\Bootstrap\Services\Auth\Remember;

use App\Models\Auth\AuthModel;
use App\Models\User\PreferencesModel;

use Translate;

class UserData
{
    static protected $type = null;

    static protected $id = null;

    static protected $user = null;

    static protected $myAccount = null;

    /**
     * Returns an array of values if the user is registered
     * Возвращает массив значений, если пользователь зарегистрирован
     * TODO: возможно поменять название...
     */
    public static function get()
    {
        if (self::$myAccount !== null) {
            return self::$myAccount;
        }

        $t = self::getAccount();

        self::$user = $t ?? self::noAuth();

        return self::$user;
    }

    public static function getAccount()
    {
        $account = Session::get('account');

        if (!empty($account['id'])) {

            $user = AuthModel::getUser($account['id']);

            if ($user['ban_list'] == RegType::BANNED_USER) {
                Action::annul($user['id']);
            }
        } else {
            $remember = Cookies::get('remember')->value();
            if ($remember ?? false) {
                Remember::check($remember);
            }
        }

        $lang = $user['lang'] ?? config('general', 'lang');
        Translate::setLang($lang);

        self::$myAccount = $user ?? self::noAuth();

        return self::$myAccount;
    }

    public static function noAuth(): array
    {
        return [
            'id'           	=> RegType::USER_ZERO_LEVEL,
            'trust_level'  	=> RegType::USER_ZERO_LEVEL,
            'scroll'       	=> RegType::USER_ZERO_LEVEL,
            'template'     	=> config('general', 'template'),
            'lang'         	=> config('general', 'lang'),
            'nsfw'			=> RegType::USER_ZERO_LEVEL,
        ];
    }

    /**
     * Checking for administrator and higher.
     * Проверка на администратора и выше.
     * 
     * @return bool
     */
    public static function checkAdmin(): bool
    {
        return self::getUserTl() == RegType::REGISTERED_ADMIN;
    }

    /**
     * Check for any unblocked user.
     * Проверка на любого незаблокированного пользователя.
     * 
     * @return int
     */
    public static function checkActiveUser()
    {
        return self::getUserTl() >= RegType::USER_FIRST_LEVEL;
    }

    /**
     * Returns the trust level if the user is registere.
     * Возвращает уровень доверия, если пользователь зарегистрирован.
     *
     * @return integer
     */
    public static function getUserTl(): int
    {
        return  self::get()['trust_level'] ?? RegType::USER_ZERO_LEVEL;
    }

    /**
     * Returns the participant id if the user is registered.
     * Возвращает id участника, если пользователь зарегистрирован.
     * 
     * @return integer
     */
    public static function getUserId(): int
    {
        return self::get()['id']; //  ?? RegType::USER_ZERO_LEVEL
    }

    /**
     * Returns the member template.
     * Возвращает шаблон участника.
     * 
     * @return array
     */
    public static function getUserTheme()
    {
        return self::get()['template'] ?? config('general', 'template');
    }

    /**
     * Returns the member template.
     * Возвращает шаблон участника.
     * @return integer
     */
    public static function getLimitingMode(): int
    {
        return self::get()['limiting_mode'];
    }

    /**
     * Returns the member's avatar file (default).
     * Возвращает файл аватара участника (по умолчанию дефолтный).
     *
     * @return string|false
     */
    public static function getUserAvatar(): string|false
    {
        return self::get()['avatar'] ?? false;
    }

    /**
     * Returns the login (nickname) of the participant.
     * Возвращает логин (никнейм) участника.
     * 
     * @return string|false
     */
    public static function getUserLogin(): string|false
    {
        return self::get()['login'] ?? false;
    }

    /**
     * Returns the localization of the participant (otherwise by default)
     * Возвращает локализацию участника (в противном случае по умолчанию).
     * 
     * @return string
     */
    public static function getUserLang(): string
    {
        return self::get()['lang'] ?? config('general', 'lang');
    }

    /**
     * Returns whether the member has scroll enabled.
     * Возвращает, включен ли скролл у участника.
     * 
     * @return int
     */
    public static function getUserScroll(): int
    {
        return self::get()['scroll'];
    }

    /**
     * Returns whether the participant's NSFW display is enabled.
     * Возвращает, включен ли показ NSFW у участника.
     *
     * @return integer
     */
    public static function getUserNSFW(): int
    {
        return self::get()['nsfw'] ?? RegType::USER_ZERO_LEVEL;
    }

    /**
     * Returns the Email address of the participant.
     * Возвращает Email участника.
     *
     * @return string
     */
    public static function getUserEmail(): string
    {
        return self::get()['email'];
    }

    /**
     * Returns whether the participant's personal template is enabled.
     * Возвращает, включен ли персональный шаблон у участника.
     *
     * @return integer
     */
    public static function getUserPostDesign()
    {
        return self::get()['post_design'] ?? false;
    }

    /**
     * Returns personal blog data (if any)
     * Возвращает данные личного блога (если он есть)
     *
     * @return array
     */
    public static function getUserBlog()
    {
        return PreferencesModel::getBlog(self::getUserId());
    }

    /**
     * Returns the personal menu (left navigation menu).
     * Возвращает персональное меню (левая меню навигации).
     *
     * @return array
     */
    public static function getUserFacets(): array
    {
        return PreferencesModel::getMenu();
    }
}
