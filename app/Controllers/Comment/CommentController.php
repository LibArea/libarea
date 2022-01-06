<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\CommentModel;
use Content, Translate;

class CommentController extends MainController
{
    private $uid;

    protected $limit = 25;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Все комментарии
    public function index($type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = CommentModel::getCommentsAllCount('user');
        $comments   = CommentModel::getCommentsAll($page, $this->limit, $this->uid, 'user');

        $result = [];
        foreach ($comments  as $ind => $row) {
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Content::text($row['comment_content'], 'text');
            $result[$ind]   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('comments'),
        ];

        return agRender(
            '/comment/comments',
            [
                'meta'  => meta($m, Translate::get('all comments'), Translate::get('comments-desc')),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'comments',
                    'type'          => $type,
                    'comments'      => $result
                ]
            ]
        );
    }
}
