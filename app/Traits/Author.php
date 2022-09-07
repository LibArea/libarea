<?php

namespace App\Traits;

use UserData;

trait Author
{
    private function selectAuthor($user_id, $user_new)
    {
        if (!$user_new) {
            return $user_id;
        }
        
        if (UserData::checkAdmin()) {
            $answer_user_new = json_decode($user_new, true);
            return $answer_user_new[0]['id'];
        }
        return $user_id;
    }
}