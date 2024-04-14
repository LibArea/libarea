<?php

declare(strict_types=1);

namespace App\Bootstrap\Services\Auth;

use App\Bootstrap\Services\User\UserData;

final class RegType
{
    // Banned
    // Забаненный
    const BANNED_USER = 1;

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

    public const RULES = ['=', '>=', '<=', '<>', '!=', '>', '<'];

    /**
     * Checking for registration by integer user type and comparison sign.
     *
     * Проверка на регистрацию по числовому типу пользователя и знаку сравнения.
     *
     * getRegType(UserRegistration::REGISTERED_COMMANDANT, '='),
     * getRegType(UserRegistration::REGISTERED_USER)
     * or getRegType([UserRegistration::BANNED_USER, UserRegistration::DELETED_USER])
     *
     * @param integer|array $type
     * @param string|null $cp
     * @return bool
     */
    public static function check(int|array $type, string|null $cp = '>='): bool
    {
        $tl = UserData::getUserTl(); // ?? self::USER_ZERO_LEVEL;

        return ((\is_integer($type) && (
            ($cp == '=' && $tl === $type) ||
            ($cp == '>=' && $tl >= $type) ||
            ($cp == '<=' && $tl >= $type) ||
            ($cp == '<>' && $tl != $type) ||
            ($cp == '!=' && $tl != $type) ||
            ($cp == '>' && $tl > $type) ||
            ($cp == '<' && $tl < $type)
        )) || (\is_array($type) && \in_array($tl, $type, true))
        );
    }
}
