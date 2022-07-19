<?php

namespace App\Models;

use DB;

class AuthModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Check for repetitions  
    // Проверка на повторы
    public static function checkRepetitions($params, $type)
    {
        $field = ($type == 'email') ? 'email' : 'login';

        $sql = "SELECT login, email FROM users WHERE $field = :params";

        return DB::run($sql, ['params' => $params])->fetch();
    }

    // Login is banned and the ban is not lifted, then ban and ip 
    // Login забанен и бан не снят, то запретить и ip
    public static function repeatIpBanRegistration($ip)
    {
        $sql = "SELECT
                    banlist_ip,
                    banlist_status
                        FROM users_banlist
                        WHERE banlist_ip = :ip AND banlist_status = 1";

        return DB::run($sql, ['ip' => $ip])->fetch();
    }

    public static function getAuthTokenByUserId($user_id)
    {
        $sql = "SELECT
                    auth_id,
                    auth_user_id,
                    auth_selector,
                    auth_hashedvalidator,
                    auth_expires
                        FROM users_auth_tokens
                        WHERE auth_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetch();
    }

    public static function insertToken($params)
    {
        $sql = "INSERT INTO users_auth_tokens(auth_user_id, auth_selector, auth_hashedvalidator, auth_expires) 
                       VALUES(:auth_user_id, :auth_selector, :auth_hashedvalidator, :auth_expires)";

        return DB::run($sql, $params);
    }

    public static function updateToken($params)
    {
        $sql = "UPDATE users_auth_tokens 
                    SET auth_selector           = :auth_selector, 
                        auth_hashedvalidator    = :auth_hashedvalidator, 
                        auth_expires            = :auth_expires
                            WHERE auth_user_id  = :auth_user_id";

        return DB::run($sql, $params);
    }

    // Get an authentication token by selector 
    // Получаем токен аутентификации по селектору
    public static function getAuthTokenBySelector($selector)
    {
        $sql = "SELECT
                    auth_id,
                    auth_user_id,
                    auth_selector,
                    auth_hashedvalidator,
                    auth_expires
                        FROM users_auth_tokens
                        WHERE auth_selector = :auth_selector";

        return DB::run($sql, ['auth_selector' => $selector])->fetch();
    }

    public static function UpdateSelector($params)
    {
        $sql = "UPDATE users_auth_tokens 
                    SET auth_hashedvalidator    = :auth_hashedvalidator,  
                        auth_expires            = :auth_expires
                            WHERE auth_selector = :auth_selector";

        return DB::run($sql, $params);
    }

    public static function deleteTokenByUserId($user_id)
    {
        $sql = "DELETE FROM users_auth_tokens WHERE auth_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }
}
