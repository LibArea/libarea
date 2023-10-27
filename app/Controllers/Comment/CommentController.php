<?php

namespace App\Controllers\Comment;

use App\Controllers\Controller;
use App\Models\{CommentModel, AnswerModel};
use Meta, Html;

class CommentController extends Controller
{
    protected $limit = 10;

    // All comments (combining answers and comments for UX)
    // Все комментарии (объединение ответов и комментариев для UX)
    public function index($sheet)
    {
        $pagesCount = CommentModel::getCommentsCount($sheet);
        $comments   = CommentModel::getComments($this->pageNumber, $this->limit, $sheet);

        $pagesCount = AnswerModel::getAnswersCount($sheet);
        $answers    = AnswerModel::getAnswers($this->pageNumber, $this->limit, $sheet);

        $m = [
            'og'    => false,
            'url'   => url('comments'),
        ];

        $mergedArr = array_merge($comments, $answers);
        usort($mergedArr, function ($a, $b) {
            return ($b['comment_date'] ?? $b['answer_date']) <=> ($a['comment_date'] ?? $a['answer_date']);
        });

        return $this->render(
            '/comment/comments',
            [
                'meta'  => Meta::get(__('meta.all_comments'), __('meta.comments_desc'), $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'sheet'         => $sheet,
                    'type'          => 'comments',
                    'comments'      => $mergedArr,
                ]
            ]
        );
    }

    // On the home page  
    // На главной странице
    public function lastComment()
    {
        $comments  = CommentModel::getComments(1, 5, 'all');

        $result = [];
        foreach ($comments as $ind => $row) {
            $row['content'] = fragment($row['comment_content'], 98);
            $row['date']    = Html::langDate($row['comment_date']);
            $result[$ind]   = $row;
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
