<?php

namespace Modules\Admin\Models;

use DB;
use PDO;

class InvitationModel extends \MainModel
{
    public static function getInvitations()
    {
        $sql = "SELECT 
                    id,
                    login,
                    avatar,
                    uid,
                    active_uid,
                    active_time
                        FROM invitation 
                        LEFT JOIN users ON active_uid = id ORDER BY id DESC";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
