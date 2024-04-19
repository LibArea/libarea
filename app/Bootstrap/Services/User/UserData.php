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

        self::getAccount();

        return self::$myAccount ?? self::noAuth();
    }

    public static function getAccount()
    {
        $account = Session::get('account');

        if (empty($account['id'])) {
		
			$remember = Cookies::get('remember')->value();
            if ($remember ?? false) {
                $user = Remember::check($remember);
            }
			
        } else {
		
			$user = AuthModel::getUser($account['id']);
            if ($user['ban_list'] == RegType::BANNED_USER) {
                Action::annul($user['id']);
            }
      
        }
		
        $lang = $user['lang'] ?? config('general', 'lang');
        Translate::setLang($lang);

		if (empty($user['id'])) {
			return self::$myAccount = self::noAuth();
		}

		Translate::setLang($user['lang']);

		return self::$myAccount = $user;
    }

    public static function noAuth(): array
    {
        return [
            'id'                => RegType::USER_ZERO_LEVEL,
            'trust_level'       => RegType::USER_ZERO_LEVEL,
            'scroll'            => RegType::USER_ZERO_LEVEL,
            'nsfw'              => RegType::USER_ZERO_LEVEL,
            'template'          => config('general', 'template'),
            'lang'              => config('general', 'lang'),
            'post_design'       => false,
            'limiting_mode'     => false,
            'avatar'            => false,
            'login'             => false,
            'email'             => false,
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
     * Returns the participant id if the user is registered.
     * Возвращает id участника, если пользователь зарегистрирован.
     * 
     * @return integer
     */
    public static function getUserId(): int
    {
        return self::get()['id'];
    }

    /**
     * Returns the trust level if the user is registere.
     * Возвращает уровень доверия, если пользователь зарегистрирован.
     *
     * @return integer
     */
    public static function getUserTl(): int
    {
        return  self::get()['trust_level'];
    }

    /**
     * Returns the member template.
     * Возвращает шаблон участника.
     * 
     * @return array
     */
    public static function getUserTheme(): string
    {
        return self::get()['template'];
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
        return self::get()['avatar'];
    }

    /**
     * Returns the login (nickname) of the participant.
     * Возвращает логин (никнейм) участника.
     * 
     * @return string|false
     */
    public static function getUserLogin(): string|false
    {
        return self::get()['login'];
    }

    /**
     * Returns the localization of the participant (otherwise by default)
     * Возвращает локализацию участника (в противном случае по умолчанию).
     * 
     * @return string
     */
    public static function getUserLang(): string
    {
        return self::get()['lang'];
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
        return self::get()['nsfw'];
    }

    /**
     * Returns the Email address of the participant.
     * Возвращает Email участника.
     *
     * @return string
     */
    public static function getUserEmail(): string|false
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
        return self::get()['post_design'];
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
