<?php

namespace App\Traits;

use App\Models\PostModel;
use App\Models\User\UserModel;

trait Views
{
    public function setPostView($post_id, $user_id)
    {
        if (!isset($_SESSION['pagenumbers'])) {
            $_SESSION['pagenumbers'] = [];
        }

        if (!isset($_SESSION['pagenumbers'][$post_id])) {
            PostModel::updateCount($post_id, 'hits');
            $_SESSION['pagenumbers'][$post_id] = $post_id;
            if ($this->user['id'] > 0) {
                PostModel::updateViews($post_id, $user_id);
            }
        }
        return true;
    }
    
    public function setProfileView($user_id)
    {
        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = [];
        }

        if (!isset($_SESSION['usernumbers'][$user_id])) {
            UserModel::userHits($user_id);
            $_SESSION['usernumbers'][$user_id] = $user_id;
        }
        return true;
    }
}