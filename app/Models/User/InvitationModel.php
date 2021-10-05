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
                    user_id,
                    user_login,
                    user_avatar,
                    uid,
                    active_uid,
                    active_time
                        FROM invitations 
                        LEFT JOIN users ON active_uid = user_id ORDER BY user_id DESC";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Создадим инвайт для участника
    public static function create($user_id, $invitation_code, $invitation_email, $add_time, $add_ip)
    {
        $sql = "UPDATE users SET user_invitation_available = (user_invitation_available + 1) WHERE user_id = :user_id";

        DB::run($sql, ['user_id' => $user_id]);

        $params = [
            'uid'               => $user_id,
            'invitation_code'   => $invitation_code,
            'invitation_email'  => $invitation_email,
            'add_time'          => $add_time,
            'add_ip'            => $add_ip,
        ];

        $sql = "INSERT INTO invitations(uid, invitation_code, invitation_email, add_time, add_ip) 
                       VALUES(:uid, :invitation_code, :invitation_email, :add_time, :add_ip)";

        return DB::run($sql, $params);
    }

    // Проверим на повтор
    public static function duplicate($user_id)
    {
        $sql = "SELECT
                    uid,
                    invitation_email
                        FROM invitations
                        WHERE uid = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Все инвайты участинка
    public static function userResult($user_id)
    {
        $sql = "SELECT 
                   uid, 
                   active_uid,
                   active_status,
                   add_time,
                   invitation_email,
                   invitation_code,                  
                   user_id,
                   user_avatar,
                   user_login
                        FROM invitations
                            LEFT JOIN users ON user_id = active_uid
                            WHERE uid = :user_id
                            ORDER BY add_time DESC";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
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
    public static function activate($inv_code, $inv_uid, $reg_ip, $active_uid)
    {
        $params = [
            'active_status'     => 1,
            'active_ip'         => $reg_ip,
            'active_time'       => date('Y-m-d H:i:s'),
            'active_uid'        => $active_uid,
            'invitation_code'   => $inv_code,
            'uid'               => $inv_uid,
        ];

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
