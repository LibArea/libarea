<?php

namespace App\Controllers\Comment;

use App\Controllers\Controller;
use App\Models\CommentModel;
use Meta, Html;

class CommentController extends Controller
{
    protected $limit = 10;

    // All comments
    // Все комменатрии
    public function index($sheet)
    {
        $pagesCount = CommentModel::getCommentsCount($sheet);
        $comments	= CommentModel::getComments($this->pageNumber, $sheet);

        $m = [
            'og'    => false,
            'url'   => url('comments'),
        ];

        return $this->render(
            '/comments/all',
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
