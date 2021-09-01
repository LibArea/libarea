<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\PostModel;
use Lori\{Content, Base};

class PostsController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit      = 100;
        $pagesCount = PostModel::getPostsAllCount($sheet);
        $posts      = PostModel::getPostsAll($page, $limit, $sheet);

        $result = array();
        foreach ($posts  as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview'] = Content::text($text[0], 'line');
            $row['date']    = lang_date($row['post_date']);
            $result[$ind]   = $row;
        }

        $meta_title = lang('Posts');
        if ($sheet == 'ban') {
            $meta_title = lang('Deleted posts');
        }

        $meta = [
            'meta_title'    => $meta_title,
            'sheet'         => 'posts',
        ];
        
        $data = [
            'sheet'         => $sheet == 'all' ? 'posts' : 'posts-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'posts'         => $result,
        ];
        
        return view('/post/posts', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
