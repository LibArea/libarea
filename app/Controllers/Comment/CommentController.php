<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\CommentModel;
use Content, Translate, Tpl, UserData;

class CommentController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Все комментарии
    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = CommentModel::getCommentsAllCount($this->user, $sheet);
        $comments   = CommentModel::getCommentsAll($page, $this->limit, $this->user, $sheet);

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

        return Tpl::agRender(
            '/comment/comments',
            [
                'meta'  => meta($m, Translate::get('all.comments'), Translate::get('comments-desc')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'comments'      => $result,
                ]
            ]
        );
    }
}
