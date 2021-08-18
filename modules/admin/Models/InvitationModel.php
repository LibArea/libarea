<?php

namespace Modules\Admin\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class InvitationModel extends MainModel
{
    public static function getInvitations()
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
}
