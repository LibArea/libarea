<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{CommentModel, UserModel};
use Lori\{Content, Config, Base};

class CommentController extends MainController
{
    // Все комментарии
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = CommentModel::getCommentAllCount();
        $comments   = CommentModel::getCommentsAll($page, $limit, $uid);

        $result = array();
        foreach ($comments  as $ind => $row) {
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Content::text($row['comment_content'], 'line');
            $result[$ind]   = $row;
        }

        $num = ' | ';
        if ($page > 1) {
            $num = sprintf(lang('page-number'), $page) . ' | ';
        }

        $data = [
            'h1'            => lang('All comments'),
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/comments',
            'sheet'         => 'comments',
            'meta_title'    => lang('All comments') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('comments-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/comment/comments', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

    // Комментарии участника
    public function userComments()
    {
        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        Base::PageError404($user);

        $comm  = CommentModel::userComments($login);

        $result = array();
        foreach ($comm as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'line');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Comments-n') . ' ' . $login,
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/comments',
            'sheet'         => 'user-comments',
            'meta_title'    => lang('Comments-n') . ' ' . $login . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Comments-n') . ' ' . $login . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/comment/comment-user', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }
}
