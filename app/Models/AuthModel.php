<?php

namespace App\Models;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;
use Lori\Base;
use Lori\Config;
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
}
