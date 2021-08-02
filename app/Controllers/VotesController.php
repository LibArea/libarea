<?php

namespace App\Controllers;

use App\Models\VotesModel;
use Hleb\Constructor\Handlers\Request;

class VotesController extends \MainController
{
    // Голосование за пост
    public function index()
    {
        $account = Request::getSession('account');
        $user_id = $account['user_id'];

        $up_id  = \Request::getPostInt('up_id');
        $type   = \Request::get('type');

        $allowed = ['post', 'comment', 'answer', 'link'];
        if (!in_array($type, $allowed)) {
            return false;
        }
 
        if ($up_id <= 0) {
            return false;
        }

        // Получаем id автора контента и проверяем, чтобы участник не голосовал за свой контент
        // $type = post / answer / comment / link
        $author_id = VotesModel::authorId($up_id, $type);
        if ($user_id == $author_id) {
            return false;
        }

        // Проверяем, голосовал ли пользователь за пост
        $info = VotesModel::voteStatus($up_id, $user_id, $type);
        if ($info) {
            return false;
        } 

        $date = date("Y-m-d H:i:s");
        $ip = Request::getRemoteAddress();

        VotesModel::saveVote($up_id, $ip, $user_id, $date, $type);
        VotesModel::saveVoteContent($up_id, $type);

        return true;
    }
}
