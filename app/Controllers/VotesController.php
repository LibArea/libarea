<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\VotesModel;

class VotesController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = UserData::getUid();
    }

    public function index()
    {
        $up_id  = Request::getPostInt('up_id');
        $type   = Request::get('type');

        $allowed = ['post', 'comment', 'answer', 'item'];
        if (!in_array($type, $allowed)) return false;

        if ($up_id <= 0) return false;

        // Проверяем, чтобы участник не голосовал за свой контент
        // We check that the participant does not vote for their content
        // $type = post / answer / comment / item
        $author_id = VotesModel::authorId($up_id, $type);
        if ($this->uid['user_id'] == $author_id) return false;

        // Проверяем, голосовал ли пользователь
        // We check whether the user voted
        $info = VotesModel::voteStatus($up_id, $this->uid['user_id'], $type);
        if ($info) return false;

        $date = date("Y-m-d H:i:s");
        $ip = Request::getRemoteAddress();

        VotesModel::saveVote($up_id, $ip, $this->uid['user_id'], $date, $type);
        VotesModel::saveVoteContent($up_id, $type);

        return true;
    }
}
