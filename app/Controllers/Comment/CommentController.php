<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\CommentModel;
use Content, Base, Translate;

class CommentController extends MainController
{
    private $uid;

    protected $limit = 25;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Все комментарии
    public function index()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = CommentModel::getCommentsAllCount('user');
        $comments   = CommentModel::getCommentsAll($page, $this->limit, $this->uid, 'user');

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
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
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
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => 'user-comments',
                    'type'          => Translate::get('comments') . ' ' . $login,
                    'comments'      => $result,
                    'user_login'    => $user['user_login'],
                ]
            ]
        );
    }
}
