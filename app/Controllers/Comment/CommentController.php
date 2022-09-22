<?php

namespace App\Controllers\Comment;

use App\Controllers\Controller;
use App\Models\CommentModel;
use Meta, Html, Content;

class CommentController extends Controller
{
    protected $limit = 25;

    // All comments
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

    public function lastComment()
    {
        $comments  = CommentModel::getCommentsAll(1, 5, $this->user, 'all');

        $result = [];
        foreach ($comments as $ind => $row) {
            $row['content'] = fragment($row['content'], 98);
            $row['date']    = Html::langDate($row['comment_date']);
            $result[$ind]   = $row;
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
