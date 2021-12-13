<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\FeedModel;
use Content, Base, Translate;

class PostsController extends MainController
{
    private $uid;

    protected $limit = 100;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = FeedModel::feedCount($this->uid, $sheet, 'admin', 'post');
        $posts      = FeedModel::feed($page, $this->limit, $this->uid, $sheet, 'admin', 'post');

        $result = [];
        foreach ($posts  as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview'] = Content::text($text[0], 'line');
            $row['date']    = lang_date($row['post_date']);
            $result[$ind]   = $row;
        }

        return view(
            '/admin/post/posts',
            [
                'meta'  => meta($m = [], $sheet == 'ban' ? Translate::get('deleted posts') : Translate::get('posts')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet == 'all' ? 'posts' : 'posts-ban',
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'posts'         => $result,
                ]
            ]
        );
    }
}
