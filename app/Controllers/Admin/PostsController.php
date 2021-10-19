<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\Admin\PostModel;
use Content, Base;

class PostsController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $limit  = 100;

        $pagesCount = PostModel::getPostsAllCount($sheet);
        $posts      = PostModel::getPostsAll($page, $limit, $sheet);

        $result = array();
        foreach ($posts  as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview'] = Content::text($text[0], 'line');
            $row['date']    = lang_date($row['post_date']);
            $result[$ind]   = $row;
        }

        $meta = meta($m = [], $sheet == 'ban' ? lang('deleted posts') : lang('posts'));
        $data = [
            'sheet'         => $sheet == 'all' ? 'posts' : 'posts-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'posts'         => $result,
        ];

        return view('/admin/post/posts', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
