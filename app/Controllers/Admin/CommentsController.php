<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\CommentModel;
use Content, Translate;

class CommentsController extends MainController
{
    private $uid;

    protected $limit = 50;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = CommentModel::getCommentsAllCount($sheet);
        $comments   = CommentModel::getCommentsAll($page, $this->limit, $this->uid, $sheet);

        $result = [];
        foreach ($comments  as $ind => $row) {
            $row['content'] = Content::text($row['comment_content'], 'text');
            $row['date']    = lang_date($row['comment_date']);
            $result[$ind]   = $row;
        }

        return agRender(
            '/admin/comment/comments',
            [
                'meta'  => meta($m = [], $sheet == 'ban' ? Translate::get('deleted comments') : Translate::get('comments')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'comments'      => $result,
                ]
            ]
        );
    }
}
