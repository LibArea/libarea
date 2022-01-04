<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\UserModel;
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

    // Комментарии участника
    public function userComments()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        $comments   = CommentModel::userComments($page, $this->limit, $user['user_id'], $this->uid['user_id']);
        $pagesCount = CommentModel::userCommentsCount($user['user_id']);

        $result = [];
        foreach ($comments as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'text');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('comments.user', ['login' => $user['user_login']]),
        ];

        return agRender(
            '/comment/comment-user',
            [
                'meta'  => meta($m, Translate::get('comments') . ' ' . $user['user_login'], Translate::get('comments') . ' ' . $user['user_login']),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'user-comments',
                    'type'          => 'comments.user',
                    'comments'      => $result,
                    'user_login'    => $user['user_login'],
                ]
            ]
        );
    }
}
