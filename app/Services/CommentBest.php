<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Services\Сheck\PostPresence;
use App\Services\Сheck\CommentPresence;
use App\Models\CommentModel;
use UserData, Access;

class CommentBest extends Base
{
    public function index()
    {
        // Get the comment data (for which the "best comment" is selected)     
        // Получим данные комментария (на который выбирается "лучший ответ")
        $comment = CommentPresence::index(Request::getPostInt('comment_id'));

        // Get the data of the post that has this comment       
        // Получим данные поста в котором есть этот ответ
        $post = PostPresence::index($comment['comment_post_id'], 'id');

        // Let's check the access. Only the staff and the author of the post can choose the best comment (without regard to time)
        // Проверим доступ. Только персонал и автор поста может выбирать лучший ответ (без учета времени)
        if ($post['post_user_id'] != UserData::getUserId() && !UserData::checkAdmin()) {
            return false;
        }

        // If the number of answers is less than 2, then we will not let you choose the best comment
        // Если количество ответов меньше 2, то не дадим выбирать лучший ответ
        if ($post['post_comments_count'] < 2) {
            return false;
        }

        // Если Лучший Ответ уже выбран, то переписываем...
        if ($post['post_lo']) {
            CommentModel::setBest($post['post_id'], $comment['comment_id'], $post['post_lo']);
            return true;
        }

        // Если Лучшего ответа нет, то первичная запись
        CommentModel::setBest($post['post_id'], $comment['comment_id'], false);
        return true;
    }
}
