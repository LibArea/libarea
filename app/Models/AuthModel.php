<?php

namespace App\Models;

use DB;
use PDO;

class AuthModel extends \MainModel
{
    // Проверка Логина на дубликаты
    public static function replayLogin($login)
    {
        $sql = "SELECT 
                    login  
                        FROM users 
                        WHERE login = :login";

        return DB::run($sql, ['login' => $login])->fetch(PDO::FETCH_ASSOC);
    }

    // Проверка Email на дубликаты
    public static function replayEmail($email)
    {
        $sql = "SELECT 
                    email  
                        FROM users 
                        WHERE email = :email";

        return DB::run($sql, ['email' => $email])->fetch(PDO::FETCH_ASSOC);
    }

    // Login забанен и бан не снят, то запретить и ip
    public static function repeatIpBanRegistration($ip)
    {
        $sql = "SELECT
                    banlist_ip,
                    banlist_status
                        FROM users_banlist
                        WHERE banlist_ip = :ip AND banlist_status = 1";

        return DB::run($sql, ['ip' => $ip])->fetch(PDO::FETCH_ASSOC);
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

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function insertToken($data)
    {
        $params = [
            'auth_user_id'          => $data['user_id'],
            'auth_selector'         => $data['selector'],
            'auth_hashedvalidator'  => $data['hashedvalidator'],
            'auth_expires'          => $data['expires'],
        ];

        $sql = "INSERT INTO users_auth_tokens(auth_user_id, auth_selector, auth_hashedvalidator, auth_expires) 
                       VALUES(:auth_user_id, :auth_selector, :auth_hashedvalidator, :auth_expires)";

        return DB::run($sql, $params);
    }

    public static function updateToken($data, $user_id)
    {
        $params = [
            'auth_user_id'          => $data['user_id'],
            'auth_selector'         => $data['selector'],
            'auth_hashedvalidator'  => $data['hashedvalidator'],
            'auth_expires'          => $data['expires'],
            'auth_user_id'          => $user_id,
        ];

        $sql = "UPDATE users_auth_tokens 
                    SET auth_user_id = :auth_user_id, 
                        auth_selector = :auth_selector, 
                        auth_hashedvalidator = :auth_hashedvalidator, 
                        auth_expires = :auth_expires
                            WHERE auth_user_id = :auth_user_id";

        return DB::run($sql, $params);
    }

    public static function deleteTokenByUserId($user_id)
    {
        $sql = "DELETE FROM users_auth_tokens WHERE auth_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

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

        return DB::run($sql, ['auth_selector' => $selector])->fetch(PDO::FETCH_ASSOC);
    }

    public static function UpdateSelector($data, $selector)
    {
        $params = [
            'auth_hashedvalidator'  => $data['hashedvalidator'],
            'auth_expires'          => $data['expires'],
            'auth_selector'         => $selector,
        ];

        $sql = "UPDATE users_auth_tokens 
                    SET auth_hashedvalidator = :auth_hashedvalidator,  auth_expires = :auth_expires
                        WHERE auth_selector = :auth_selector";

        return DB::run($sql, $params);
    }
}
