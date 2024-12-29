<?php

declare(strict_types=1);

namespace App\Controllers\Comment;

use Hleb\Base\Controller;
use App\Models\CommentModel;
use Meta, Html;

class CommentController extends Controller
{
    protected $limit = 10;

    public function all(): void
    {
        $this->callIndex('all');
    }

    public function deleted(): void
    {
        $this->callIndex('deleted');
    }

    /**
     * All comments
     * Все комментарии
     *
     * @param string $sheet
     * @return void
     */
    public function callIndex(string $sheet): void
    {
        $pagesCount = CommentModel::getCommentsCount($sheet);
        $comments   = CommentModel::getComments(Html::pageNumber(), $sheet);

        $m = [
            'og'    => false,
            'url'   => url('comments'),
        ];

        render(
            '/comments/all',
            [
                'meta'  => Meta::get(__('meta.all_comments'), __('meta.all_comments_desc'), $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'sheet'         => $sheet,
                    'type'          => 'all_comments',
                    'comments'      => $comments,
                ]
            ]
        );
    }
}
