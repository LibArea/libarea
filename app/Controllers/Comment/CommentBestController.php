<?php

declare(strict_types=1);

namespace App\Controllers\Comment;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\{PostPresence, CommentPresence};
use App\Models\CommentModel;

class CommentBestController extends Controller
{
    /**
     * Selecting the best answer
     * Выбор лучшего ответа
     *
     * @return void
     */
    public function index(): void
    {
        // Get the comment data (for which the "best comment" is selected)     
        // Получим данные комментария (на который выбирается "лучший ответ")
        $comment = CommentPresence::index(Request::post('comment_id')->asInt());

        // Get the data of the post that has this comment       
        // Получим данные поста в котором есть этот ответ
        $post = PostPresence::index($comment['comment_post_id'], 'id');

        // Let's check the access. Only the staff and the author of the post can choose the best comment (without regard to time)
        // Проверим доступ. Только персонал и автор поста может выбирать лучший ответ (без учета времени)
        if ($post['post_user_id'] != $this->container->user()->id() && !$this->container->user()->admin()) {
            return;
        }

        // If the number of answers is less than 2, then we will not let you choose the best comment
        // Если количество ответов меньше 2, то не дадим выбирать лучший ответ
        if ($post['post_comments_count'] < 2) {
            return;
        }

        // If the Best Answer has already been selected, then rewrite it...
        // Если Лучший Ответ уже выбран, то переписываем...
        if ($post['post_lo']) {
            CommentModel::setBest($post['post_id'], $comment['comment_id'], $post['post_lo']);
            return;
        }

        // If there is no Best Answer, then the primary entry
        // Если Лучшего ответа нет, то первичная запись
        CommentModel::setBest($post['post_id'], $comment['comment_id'], false);
    }
}
