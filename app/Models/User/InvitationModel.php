<?php

namespace App\Models\User;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class InvitationModel extends MainModel
{
    public static function get()
    {
        $sql = "SELECT 
                    id,
                    login,
                    avatar,
                    uid,
                    invitation_email,
                    add_time,
                    active_uid,
                    active_time
                        FROM invitations 
                        LEFT JOIN users ON active_uid = id ORDER BY id DESC";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Создадим инвайт для участника
    public static function create($params)
    {
        $sql = "INSERT INTO invitations(uid, 
                    invitation_code, 
                    invitation_email, 
                    add_time, 
                    add_ip) 
                       VALUES(:uid, 
                           :invitation_code, 
                           :invitation_email, 
                           :add_time, 
                           :add_ip)";

        DB::run($sql, $params);

        $sql = "UPDATE users SET invitation_available = (invitation_available + 1) WHERE id = :uid";

        DB::run($sql, ['uid' => $params['uid']]);

        return true;
    }

    // Проверим на повтор
    public static function duplicate($email)
    {
        $sql = "SELECT
                    invitation_email
                        FROM invitations
                        WHERE invitation_email = :email";

        return DB::run($sql, ['email' => $email])->fetch(PDO::FETCH_ASSOC);
    }

    // Все инвайты участинка
    public static function userResult($uid)
    {
        $sql = "SELECT 
                   uid, 
                   active_uid,
                   active_status,
                   add_time,
                   invitation_email,
                   invitation_code,                  
                   id,
                   avatar,
                   login
                        FROM invitations
                            LEFT JOIN users ON id = active_uid
                            WHERE uid = :uid
                            ORDER BY add_time DESC";

        return DB::run($sql, ['uid' => $uid])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Проверим не активированный инвайт
    public static function available($invitation_code)
    {
        $sql = "SELECT
                    uid,
                    active_status,
                    invitation_code,
                    invitation_email
                        FROM invitations
                        WHERE invitation_code = :code AND active_status = 0";

        return DB::run($sql, ['code' => $invitation_code])->fetch(PDO::FETCH_ASSOC);
    }

    // Проверим не активированный инвайт и поменяем статус
    public static function activate($params)
    {
        $sql = "UPDATE invitations SET 
                    active_status   = :active_status,
                    active_ip       = :active_ip,
                    active_time     = :active_time,
                    active_uid      = :active_uid
                        WHERE invitation_code = :invitation_code
                            AND uid = :uid";

        DB::run($sql, $params);
    }
}
