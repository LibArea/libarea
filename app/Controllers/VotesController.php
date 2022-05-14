<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\VotesModel;

class VotesController extends Controller
{
    public function index()
    {
        $up_id  = Request::getPostInt('content_id');
        $type   = Request::getPost('type');

        $allowed = ['post', 'comment', 'answer', 'item', 'reply'];
        if (!in_array($type, $allowed)) return false;
        if ($up_id <= 0) return false;

        // Проверяем, чтобы участник не голосовал за свой контент
        // We check that the participant does not vote for their content
        // $type = post / answer / comment / item
        $author_id = VotesModel::authorId($up_id, $type);
        if ($this->user['id'] == $author_id) return false;

        // Проверяем, голосовал ли пользователь
        // We check whether the user voted
        $info = VotesModel::voteStatus($up_id, $this->user['id'], $type);
        if ($info) return false;

        $ip = Request::getRemoteAddress();

        VotesModel::saveVote($up_id, $ip, $this->user['id'], $type);
        VotesModel::saveVoteContent($up_id, $type);

        return true;
    }
}
