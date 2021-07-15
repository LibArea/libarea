<?php

namespace App\Models;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;
use DB;
use PDO;

class AuthModel extends \MainModel
{
    // Проверка Логина на дубликаты
    public static function replayLogin($login)
    {
        $result = XD::select('*')->from(['users'])->where(['login'], '=', $login)->getSelectOne();
        
        if ($result) {
            return false;
        }
        return true;
    }
    
    // Проверка Email на дубликаты
    public static function replayEmail($email)
    {
        $result = XD::select('*')->from(['users'])->where(['email'], '=', $email)->getSelectOne();
        
        if ($result) {
            return false;
        }
        return true;
    }
    
    // Login забанен и бан не снят, то запретить и ip
    public static function repeatIpBanRegistration($ip)
    {
        $result = XD::select('*')->from(['users_banlist'])->where(['banlist_ip'], '=', $ip)->and(['banlist_status'], '=', 1)->getSelectOne();

        if ($result) {
            return false;
        }
        return true;
    }
    
    // Поля в таблице users_auth_tokens
    // auth_id,	auth_user_id,	auth_selector,	auth_hashedvalidator,	auth_expires	
    public static function getAuthTokenByUserId($user_id)
    {
        return XD::select('*')->from(['users_auth_tokens'])
                ->where(['auth_user_id'], '=', $user_id)->getSelectOne();
    }
    
    public static function insertToken($data)
    {
        return  XD::insertInto(['users_auth_tokens'], '(', ['auth_user_id'], ',', ['auth_selector'], ',', ['auth_hashedvalidator'], ',', ['auth_expires'], ')')->values( '(', XD::setList([$data['user_id'], $data['selector'], $data['hashedvalidator'], $data['expires']]), ')' )->run();
    }
    
    public static function updateToken($data, $uid)
    {
        return  XD::update(['users_auth_tokens'])->set(['auth_user_id'], '=', $data['user_id'], ',', ['auth_selector'], '=', $data['selector'], ',', ['auth_hashedvalidator'], '=', $data['hashedvalidator'], ',', ['auth_expires'], '=', $data['expires'])->where(['auth_user_id'], '=', $uid)->run();
    }
    
    public static function deleteTokenByUserId($uid)
    {
        return XD::deleteFrom(['users_auth_tokens'])->where(['auth_user_id'], '=', $uid)->run(); 
    }
    
    // Получаем токен аутентификации по селектору
    public static function getAuthTokenBySelector($selector)
    {
        return XD::select('*')->from(['users_auth_tokens'])->where(['auth_selector'], '=', $selector)->getSelectOne();
    }
    
    public static function UpdateSelector($data, $selector)
    {
       return  XD::update(['users_auth_tokens'])->set(['auth_hashedvalidator'], '=', $data['hashedvalidator'], ',', ['auth_expires'], '=', $data['expires'])->where(['auth_selector'], '=', $selector)->run();
    }
    
}
