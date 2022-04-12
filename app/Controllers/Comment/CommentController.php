<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\CommentModel;
use Content, Translate, Tpl, Meta, UserData;

class CommentController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Все комментарии
    public function index($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();

        $pagesCount = CommentModel::getCommentsAllCount($this->user, $sheet);
        $comments   = CommentModel::getCommentsAll($pageNumber, $this->limit, $this->user, $sheet);

        $m = [
            'og'    => false,
            'url'   => getUrlByName('comments'),
        ];

        return Tpl::agRender(
            '/comment/comments',
            [
                'meta'  => Meta::get(Translate::get('all.comments'), Translate::get('comments.desc'), $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'comments'      => $comments,
                ]
            ]
        );
    }
}
