<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\CommentModel;
use Content, Base, Translate;

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

        $result = [];
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

        return view(
            '/comment/comments',
            [
                'meta'  => meta($m, Translate::get('all comments'), Translate::get('comments-desc')),
                'uid'   => $uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'sheet'         => 'comments',
                    'comments'      => $result
                ]
            ]
        );
    }

    // Комментарии участника
    public function userComments()
    {
        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        $comments  = CommentModel::userComments($login);

        $result = [];
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

        return view(
            '/comment/comment-user',
            [
                'meta'  => meta($m, Translate::get('comments') . ' ' . $login, Translate::get('comments') . ' ' . $login),
                'uid'   => Base::getUid(),
                'data'  => [
                    'sheet'         => 'user-comments',
                    'comments'      => $result,
                    'user_login'    => $user['user_login'],
                ]
            ]
        );
    }
}
