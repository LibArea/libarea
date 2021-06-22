<?php

namespace App\Controllers;
use App\Models\VotesModel;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class VotesController extends \MainController
{

   // Голосование за пост
    public function index($type)
    {
        // id того, кто голосует
        $account = Request::getSession('account');
        $user_id = $account['user_id'];
        
        $content_id = \Request::getPostInt($type . '_id');

        if ($content_id <= 0) {
            return false;
        }

        // Получаем id автора контента и проверяем, чтобы участник не голосовал за свой
        // $type = post / answer / comment / link
        $author_id = VotesModel::authorId($content_id, $type);
        if ($user_id == $author_id) {
           return false;
        }    
        
        // Проверяем, голосовал ли пользователь за пост
        VotesModel::voteStatus($content_id, $user_id, $type);   
        
        $date = date("Y-m-d H:i:s");
        $ip = Request::getRemoteAddress();
        
        VotesModel::saveVote($content_id, $ip, $user_id, $date, $type);
        VotesModel::saveVoteContent($content_id, $type);
     
        if($type)
     
        // Добавим в чат и в поток
        $data_flow = [
            'flow_action_type'  => 'vote_'.$type,
            'flow_content'      => '',
            'flow_user_id'      => $user_id,
            'flow_pubdate'      => $date,
            'flow_url'          => '', 
            'flow_target_id'    => $content_id,
            'flow_space_id'     => 0,
            'flow_tl'           => 0,
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);

        return true;
        
    }
 
}