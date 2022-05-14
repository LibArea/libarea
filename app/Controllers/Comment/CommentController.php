<?php

namespace App\Controllers\Comment;

use App\Controllers\Controller;
use App\Models\CommentModel;
use Meta;

class CommentController extends Controller
{
    protected $limit = 25;

    // Все комментарии
    public function index($sheet)
    {
        $pagesCount = CommentModel::getCommentsAllCount($this->user, $sheet);
        $comments   = CommentModel::getCommentsAll($this->pageNumber, $this->limit, $this->user, $sheet);

        $m = [
            'og'    => false,
            'url'   => url('comments'),
        ];

        return $this->render(
            '/comment/comments',
            [
                'meta'  => Meta::get(__('meta.all_comments'), __('meta.comments_desc'), $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'sheet'         => $sheet,
                    'type'          => 'comments',
                    'comments'      => $comments,
                ]
            ]
        );
    }
}
