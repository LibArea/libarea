<?php

declare(strict_types=1);

namespace App\Models\User;

use Hleb\Static\Request;
use Hleb\Base\Model;
use Hleb\Static\DB;

class InvitationModel extends Model
{
    public static function get()
    {
        $sql = "SELECT 
                    id,
                    login,
                    avatar,
                    uid,
                    invitation_email,
                    invitation_date,
                    active_uid,
                    active_time
                        FROM invitations 
                        LEFT JOIN users ON active_uid = id ORDER BY id DESC";

        return DB::run($sql)->fetchAll();
    }

    // Создадим инвайт для участника
    public static function create($invitation_code, $invitation_email)
    {
        $user_id = self::container()->user()->id();

        $params = [
            'uid'               => $user_id,
            'invitation_code'   => $invitation_code,
            'invitation_email'  => $invitation_email,
            'add_ip'            => Request::getUri()->getIp(),
        ];

        $sql = "INSERT INTO invitations(uid, 
                    invitation_code, 
                    invitation_email, 
                    add_ip) 
                       VALUES(:uid, 
                           :invitation_code, 
                           :invitation_email, 
                           :add_ip)";

        DB::run($sql, $params);

        $sql = "UPDATE users SET invitation_available = (invitation_available + 1) WHERE id = :uid";

        DB::run($sql, ['uid' => $user_id]);

        return true;
    }

    // Проверим на повтор
    public static function duplicate($email)
    {
        $sql = "SELECT
                    invitation_email
                        FROM invitations
                        WHERE invitation_email = :email";

        return DB::run($sql, ['email' => $email])->fetch();
    }

    // Все инвайты участинка
    public static function userResult()
    {
        $sql = "SELECT 
                   uid, 
                   active_uid,
                   active_status,
                   invitation_date,
                   invitation_email,
                   invitation_code,                  
                   id,
                   avatar,
                   login
                        FROM invitations
                            LEFT JOIN users ON id = active_uid
                            WHERE uid = :user_id
                            ORDER BY invitation_date DESC";

        return DB::run($sql, ['user_id' => self::container()->user()->id()])->fetchAll();
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

        return DB::run($sql, ['code' => $invitation_code])->fetch();
    }

    // Проверим не активированный инвайт и поменяем статус
    public static function activate($inv_uid, $active_uid, $invitation_code = null)
    {
        $params = [
            'uid'               => $inv_uid,
            'active_status'     => 1,
            'active_ip'         => Request::getUri()->getIp(),
            'active_time'       => date('Y-m-d H:i:s'),
            'active_uid'        => $active_uid,
            'invitation_code'   => $invitation_code,
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
