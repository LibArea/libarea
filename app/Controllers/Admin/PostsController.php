<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\FeedModel;
use Content, Translate, Tpl;

class PostsController extends MainController
{
    protected $limit = 50;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $sort       = $sheet == 'admin.posts.all' ? 0 : 1;
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $sort);
        $posts      = FeedModel::feed($page, $this->limit, $this->user, $sheet, $sort);

        $result = [];
        foreach ($posts  as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview'] = Content::text($text[0], 'line');
            $row['date']    = lang_date($row['post_date']);
            $result[$ind]   = $row;
        }

        return Tpl::agRender(
            '/admin/post/posts',
            [
                'meta'  => meta($m = [], $sheet == 'ban' ? Translate::get('deleted posts') : Translate::get('posts')),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'posts'         => $result,
                ]
            ]
        );
    }
}
