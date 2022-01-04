<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class BanUserModel extends MainModel
{
    // Находит ли пользователь в бан- листе и рабанен ли был он
    public static function isBan($user_id)
    {
        $sql = "SELECT 
                    banlist_user_id, 
                    banlist_status,
                    banlist_int_num
                        FROM users_banlist 
                        WHERE banlist_user_id = :user_id AND banlist_status = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function setBanUser($user_id)
    {
        $sql = "SELECT 
                    banlist_user_id,
                    banlist_status
                        FROM users_banlist WHERE banlist_user_id = :user_id ";

        $sample = DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
        $num    = DB::run($sql, ['user_id' => $user_id])->rowCount();

        if ($num != 0) {

            $result = [];
            foreach ($sample as $row) {
                $status = $row['banlist_status'];
            }

            if ($status == 0) {
                // Забанить повторно
                $sql = "UPDATE users_banlist
                            SET banlist_int_num = (banlist_int_num + 1), banlist_status = 1
                                WHERE banlist_user_id = :user_id";

                DB::run($sql, ['user_id' => $user_id]);

                self::setUserBanList($user_id, 1);
            } else {
                // Разбанить
                $sql = "UPDATE users_banlist
                            SET banlist_status = 0
                                WHERE banlist_user_id = :user_id";

                DB::run($sql, ['user_id' => $user_id]);

                self::setUserBanList($user_id, 0);
            }
        } else {
            // Занесем ip регистрации    
            $sql = "SELECT 
                        user_id, 
                        user_reg_ip
                            FROM users WHERE user_id = :user_id";

            $user = DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);

            $params = [
                'banlist_user_id'       => $user_id,
                'banlist_ip'            => $user['user_reg_ip'],
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

            self::setUserBanList($user_id, 1);
        }

        return true;
    }

    // Изменим отмеку о занесении в бан-лист
    public static function setUserBanList($user_id, $status)
    {
        $sql = "UPDATE users 
                    SET user_ban_list = :status
                        WHERE user_id = :user_id";

        return  DB::run($sql, ['status' => $status, 'user_id' => $user_id]);
    }
}
