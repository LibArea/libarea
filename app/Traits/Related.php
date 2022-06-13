<?php

namespace App\Traits;

use Hleb\Constructor\Handlers\Request;

trait Related
{
    private function relatedPost()
    {
        $data = Request::getPost();
        $json_post  = $data['post_select'] ?? [];
        $arr_post   = json_decode($json_post, true);
        if ($arr_post) {
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
        }
        return implode(',', $id ?? []);
    }
}