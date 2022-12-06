<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Services\Сheck\PostPresence;
use App\Services\Сheck\AnswerPresence;
use App\Models\{AnswerModel, PostModel};
use UserData, Access;

class AnswerBest extends Base
{
    protected $user;

    public function __construct()
    {
        $this->user = UserData::get();
    } 
    
    public function index()
    {
        // Get the answer data (for which the "best answer" is selected)     
        // Получим данные ответа (на который выбирается "лучший ответ")
        $answer = AnswerPresence::index(Request::getPostInt('answer_id'));

        // Get the data of the post that has this answer       
        // Получим данные поста в котором есть этот ответ
        $post = PostPresence::index($answer['answer_post_id'], 'id');
        
        // Let's check the access. Only the staff and the author of the post can choose the best answer (without regard to time)
        // Проверим доступ. Только персонал и автор поста может выбирать лучший ответ (без учета времени)
        if (Access::author('post', $post, 0) == false) {
            return false;
        }        

        // If the number of answers is less than 2, then we will not let you choose the best answer
        // Если количество ответов меньше 2, то не дадим выбирать лучший ответ
        if ($post['post_answers_count'] < 2) {
            return false;
        }

        // Если Лучший Ответ уже выбран, то переписываем...
        if ($post['post_lo']) {
            AnswerModel::setBest($post['post_id'], $answer['answer_id'], $post['post_lo']);
            return true;
        }
        
        // Если Лучшего ответа нет, то первичная запись
        AnswerModel::setBest($post['post_id'], $answer['answer_id'], false);
        return true;
    }
}
