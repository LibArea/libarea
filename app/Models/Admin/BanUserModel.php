<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class BanUserModel extends MainModel
{
    // Находит ли пользователь в бан- листе и рабанен ли был он
    public static function isBan($uid)
    {
        $sql = "SELECT 
                    banlist_user_id, 
                    banlist_status,
                    banlist_int_num
                        FROM users_banlist 
                        WHERE banlist_user_id = :uid AND banlist_status = 1";

        return DB::run($sql, ['uid' => $uid])->fetch(PDO::FETCH_ASSOC);
    }

    public static function setBanUser($uid)
    {
        $sql = "SELECT 
                    banlist_user_id,
                    banlist_status
                        FROM users_banlist WHERE banlist_user_id = :uid ";

        $sample = DB::run($sql, ['uid' => $uid])->fetchAll(PDO::FETCH_ASSOC);
        $num    = DB::run($sql, ['uid' => $uid])->rowCount();

        if ($num != 0) {

            $result = [];
            foreach ($sample as $row) {
                $status = $row['banlist_status'];
            }

            if ($status == 0) {
                // Забанить повторно
                $sql = "UPDATE users_banlist
                            SET banlist_int_num = (banlist_int_num + 1), banlist_status = 1
                                WHERE banlist_user_id = :uid";

                DB::run($sql, ['uid' => $uid]);

                self::setUserBanList($uid, 1);
            } else {
                // Разбанить
                $sql = "UPDATE users_banlist
                            SET banlist_status = 0
                                WHERE banlist_user_id = :uid";

                DB::run($sql, ['uid' => $uid]);

                self::setUserBanList($uid, 0);
            }
        } else {
            // Занесем ip регистрации    
            $sql = "SELECT 
                        id, 
                        reg_ip
                            FROM users WHERE id = :uid";

            $user = DB::run($sql, ['uid' => $uid])->fetch(PDO::FETCH_ASSOC);

            $params = [
                'banlist_user_id'       => $uid,
                'banlist_ip'            => $user['reg_ip'],
                'banlist_bandate'       => date("Y-m-d H:i:s"),
                'banlist_int_num'       => 1,
                'banlist_int_period'    => '',
                'banlist_status'        => 1,
                'banlist_autodelete'    => 0,
                'banlist_cause'         => '',
            ];

            $sql = "INSERT INTO users_banlist(banlist_user_id, 
                        banlist_ip, 
                        banlist_bandate, 
                        banlist_int_num,
                        banlist_int_period,
                        banlist_status,
                        banlist_autodelete,
                        banlist_cause) 
                            VALUES(:banlist_user_id, 
                                :banlist_ip, 
                                :banlist_bandate, 
                                :banlist_int_num,
                                :banlist_int_period,
                                :banlist_status,
                                :banlist_autodelete,
                                :banlist_cause)";

            DB::run($sql, $params);

            self::setUserBanList($uid, 1);
        }

        return true;
    }

    // Изменим отмеку о занесении в бан-лист
    public static function setUserBanList($uid, $status)
    {
        $sql = "UPDATE users 
                    SET user_ban_list = :status
                        WHERE user_id = :uid";

        return  DB::run($sql, ['status' => $status, 'uid' => $uid]);
    }
}
