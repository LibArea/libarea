<?php

namespace App\Controllers;
use App\Models\AnswerModel;
use App\Models\FlowModel;
use App\Models\VotesAnswerModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;
 
class VotesAnswController extends \MainController
{

   // Голосование за комментарий
    public function votes()
    {
        
        $answ_id = \Request::getPostInt('answ_id');
 
        // Проверяем
        if (!$answ_id)
        {
            return false;
        }

        // id того, кто голосует за ответ
        $account = Request::getSession('account');
        $user_id = $account['user_id'];
        
        // Информация об ответе
        $answ_info = VotesAnswerModel::infoAnsw($answ_id);
        
        // Пользователь не должен голосовать за свой ответ
        if ($user_id == $answ_info['answer_user_id']) {
           return false;
        }    
                      
        // Проверяем, голосовал ли пользователь за ответ
        $userup = VotesAnswerModel::getVoteStatus($answ_info['answer_id'], $user_id);   
        
        if($userup == 1) {
            
            // далее удаление строки в таблице голосования за ответ
            // далее уменьшаем на -1 количество комментариев в самом ответе
            // см. код ниже. А пока:
            
            return false;
            
        } else {
            
            $up = 1;
            $date = date("Y-m-d H:i:s");
            $ip = Request::getRemoteAddress();
            VotesAnswerModel::saveVoteAnsw($answ_id, $up, $ip, $user_id, $date);
         
            // Получаем количество votes комментария    
            $votes_num = $answ_info['answer_votes'];
            $votes = $votes_num + 1;
          
            // Записываем новое значение Votes в строку комментария по id
            XD::update(['answers'])->set(['answer_votes'], '=', $votes)->where(['answer_id'], '=', $answ_id)->run();

            // Добавим в чат и в поток
            $data_flow = [
                'flow_action_id'    => 7, // в чат добавим ответ
                'flow_content'      => '',
                'flow_user_id'      => $user_id,
                'flow_pubdate'      => $date,
                'flow_url'          => '', 
                'flow_target_id'    => $answ_id,
                'flow_about'        => lang('add_up_answ'),
                'flow_space_id'     => 0,
                'flow_tl'           => 0,
                'flow_ip'           => $ip, 
            ];
            FlowModel::FlowAdd($data_flow);
 
            return false;
        } 
    }
 
}