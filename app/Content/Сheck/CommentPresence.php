<?php

declare(strict_types=1);

namespace App\Content\Сheck;

use App\Models\CommentModel;

class CommentPresence
{
    public static function index(int $comment_id): array
    {
        $comment = CommentModel::getCommentId($comment_id);

        notEmptyOrView404($comment);

        return $comment;
    }
}
