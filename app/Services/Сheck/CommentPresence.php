<?php

declare(strict_types=1);

namespace App\Services\Сheck;

use App\Models\CommentModel;

class CommentPresence
{
    public static function index(int $comment_id) : array
    {
        $comment = CommentModel::getCommentsId($comment_id);;
        
        notEmptyOrView404($comment);

        return $comment;
    }
}
