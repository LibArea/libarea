<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\CommentModel;
use Content, Base, Translate;

class CommentsController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit      = 100;
        $pagesCount = CommentModel::getCommentsAllCount($sheet);
        $comments   = CommentModel::getCommentsAll($page, $limit, $uid, $sheet);

        $result = array();
        foreach ($comments  as $ind => $row) {
            $row['content'] = Content::text($row['comment_content'], 'text');
            $row['date']    = lang_date($row['comment_date']);
            $result[$ind]   = $row;
        }

        return view(
            '/admin/comment/comments',
            [
                'meta'  => meta($m = [], $sheet == 'ban' ? Translate::get('deleted comments') : Translate::get('comments-n')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => $sheet == 'all' ? 'comments-n' : 'comments-ban',
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'comments'      => $result,
                ]
            ]
        );
    }
}
