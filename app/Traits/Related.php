<?php

namespace App\Traits;

use Hleb\Constructor\Handlers\Request;

trait Related
{
    private function relatedPost()
    {
        $data = Request::getPost();
        $json_post  = $data['post_select'] ?? false;
        if ($json_post) {
            $arr_post   = json_decode($json_post, true);
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
            return implode(',', $id ?? []);
        }
        return false;
    }
}
