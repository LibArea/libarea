<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\CommentModel;
use Content, Base;

class CommentController extends MainController
{
    // Все комментарии
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = CommentModel::getCommentsAllCount('user');
        $comments   = CommentModel::getCommentsAll($page, $limit, $uid, 'user');

        $result = array();
        foreach ($comments  as $ind => $row) {
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Content::text($row['comment_content'], 'line');
            $result[$ind]   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('comments'),
        ];
        $meta = meta($m, lang('all comments'), lang('comments-desc'));

        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'sheet'         => 'comments',
            'comments'      => $result
        ];

        return view('/comment/comments', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Комментарии участника
    public function userComments()
    {
        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        Base::PageError404($user);

        $comments  = CommentModel::userComments($login);

        $result = array();
        foreach ($comments as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'line');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('comments.user', ['login' => $login]),
        ];
        $meta = meta($m, lang('comments-n') . ' ' . $login, lang('comments-n') . ' ' . $login);

        $data = [
            'h1'            => lang('comments-n') . ' ' . $login,
            'sheet'         => 'user-comments',
            'comments'      => $result
        ];

        return view('/comment/comment-user', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
