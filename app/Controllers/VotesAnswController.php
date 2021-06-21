<?php

namespace App\Controllers;
use App\Models\AnswerModel;
use App\Models\FlowModel;
use App\Models\VotesAnswerModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;
use Lori\Base;
 
class VotesAnswController extends \MainController
{

   // Голосование за ответ
    public function votes()
    {
        $answer_id  = \Request::getPostInt('answ_id');
        $uid        = Base::getUid();

        // Информация об ответе
        $answ_info = VotesAnswerModel::infoAnswerId($answer_id);
        
        // Пользователь не должен голосовать за свой ответ
        if ($uid['id'] == $answ_info['answer_user_id']) {
           return false;
        }    
                      
        // Проверяем, голосовал ли пользователь за ответ
        $userup = VotesAnswerModel::getVoteStatus($answ_info['answer_id'], $uid['id']);   
        
        if($userup == 1) {
            return false;
        }
            
        $up = 1;
        $date = date("Y-m-d H:i:s");
        $ip = Request::getRemoteAddress();
        
        VotesAnswerModel::saveVoteUp($answer_id, $up, $ip, $uid['id'], $date);
     
        VotesAnswerModel::saveVoteAnswer($answer_id);

        // Добавим в чат и в поток
        $data_flow = [
            'flow_action_id'    => 7, // в чат добавим ответ
            'flow_content'      => '',
            'flow_user_id'      => $uid['id'],
            'flow_pubdate'      => $date,
            'flow_url'          => '', 
            'flow_target_id'    => $answer_id,
            'flow_about'        => lang('add_up_answ'),
            'flow_space_id'     => 0,
            'flow_tl'           => 0,
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);
        return true;
        
    }
 
}