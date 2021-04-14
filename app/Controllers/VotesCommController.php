<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\FlowModel;
use App\Models\VotesCommentModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class VotesCommController extends \MainController
{

   // Голосование за комментарий
    public function votes()
    {
        
        $comm_id = \Request::getPostInt('comm_id');
 
        // Проверяем
        if (!$comm_id)
        {
            return false;
        }

        // id того, кто голосует за комментарий
        $account = Request::getSession('account');
        $user_id = $account['user_id'];
        
        // Информация об комментарии
        $comm_info = VotesCommentModel::infoComm($comm_id);
        
        // Пользователь не должен голосовать за свой комментарий
        if ($user_id == $comm_info['comment_user_id']) {
           return false;
        }    
                      
        // Проверяем, голосовал ли пользователь за комментарий
        $userup = VotesCommentModel::getVoteStatus($comm_info['comment_id'], $user_id);   
        
        if($userup == 1) {
            
            // далее удаление строки в таблице голосования за комментарии
            // далее уменьшаем на -1 количество комментариев в самом комментарии
            // см. код ниже. А пока:
            
            return false;
            
        } else {
            
            $up = 1;
            $date = date("Y-m-d H:i:s");
            $ip = Request::getRemoteAddress();
            VotesCommentModel::saveVote($comm_id, $up, $ip, $user_id, $date);
         
            // Получаем количество votes комментария    
            $votes_num = $comm_info['comment_votes'];
            $votes = $votes_num + 1;
          
            // Записываем новое значение Votes в строку комментария по id
            XD::update(['comments'])->set(['comment_votes'], '=', $votes)->where(['comment_id'], '=', $comm_id)->run();

            // Добавим в чат и в поток
            $data_flow = [
                'flow_action_id'    => 4, // чат
                'flow_content'      => '',
                'flow_user_id'      => $user_id,
                'flow_pubdate'      => $date,
                'flow_url'          => '', 
                'flow_target_id'    => $comm_id,
                'flow_about'        => lang('add_up_comm'),
                'flow_space_id'     => 0,
                'flow_tl'           => 0,
                'flow_ip'           => $ip, 
            ];
            FlowModel::FlowAdd($data_flow);
 
            return false;
        } 
    }
 
}