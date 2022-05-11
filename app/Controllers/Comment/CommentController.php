<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\CommentModel;
use Tpl, Meta, UserData;

class CommentController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Все комментарии
    public function index($sheet)
    {
        $pageNumber = Tpl::pageNumber();

        $pagesCount = CommentModel::getCommentsAllCount($this->user, $sheet);
        $comments   = CommentModel::getCommentsAll($pageNumber, $this->limit, $this->user, $sheet);

        $m = [
            'og'    => false,
            'url'   => url('comments'),
        ];

        return Tpl::LaRender(
            '/comment/comments',
            [
                'meta'  => Meta::get(__('meta.all_comments'), __('meta.comments_desc'), $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'sheet'         => $sheet,
                    'type'          => 'comments',
                    'comments'      => $comments,
                ]
            ]
        );
    }
}
