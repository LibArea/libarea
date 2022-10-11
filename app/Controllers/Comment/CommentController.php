<?php

namespace App\Controllers\Comment;

use App\Controllers\Controller;
use App\Models\{CommentModel, AnswerModel};
use Meta, Html, Content;

class CommentController extends Controller
{
    protected $limit = 10;

    // All comments (combining answers and comments for UX)
    // Все комментарии (объединение ответов и комментариев для UX)
    public function index($sheet)
    {
        $pagesCount = CommentModel::getCommentsCount($this->user, $sheet);
        $comments   = CommentModel::getComments($this->pageNumber, $this->limit, $this->user, $sheet);

        $pagesCount = AnswerModel::getAnswersCount($sheet);
        $answers    = AnswerModel::getAnswers($this->pageNumber, $this->limit, $this->user, $sheet);

        $m = [
            'og'    => false,
            'url'   => url('comments'),
        ];

        $mergedArr = array_merge($comments, $answers);    

        $orderBy = [
            'comment_date' => 'desc',
            'answer_date' => 'desc',
        ];
         
        uasort($mergedArr, function ($a, $b) use ($orderBy) {
            $result = 0;
         
            foreach ($orderBy as $key => $value) {
         
                if ($a[$key] == $b[$key]) {
                    continue;
                }
         
                $result = ($a[$key] < $b[$key]) ? -1 : 1;
         
                if ($value == 'desc') {
                    $result = -$result;
                }
         
                break;
            }
         
            return $result;
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

    public function lastComment()
    {
        $comments  = CommentModel::getComments(1, 5, $this->user, 'all');

        $result = [];
        foreach ($comments as $ind => $row) {
            $row['content'] = fragment($row['content'], 98);
            $row['date']    = Html::langDate($row['comment_date']);
            $result[$ind]   = $row;
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
