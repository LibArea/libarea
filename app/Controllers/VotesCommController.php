<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\VotesCommentModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class VotesCommController extends \MainController
{

   // Голосование за комментарий
    public function votes()
    {
        // Авторизировались или нет
        if (!Request::getSession('account'))
        {
            return false;
        }  
        
        $comm_id = (int)Request::getInt('id');
 
        // Проверяем
        if (!$comm_id)
        {
            return false;
        }

        // id того, кто госует за комментарий
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
            $dt = date("Y-m-d H:i:s");
            $ip = Request::getRemoteAddress();
            VotesCommentModel::saveVote($comm_id, $up, $ip, $user_id, $dt);
         
            // Получаем количество votes комментария    
            $votes_num = $comm_info['comment_votes'];
            $votes = $votes_num + 1;
          
            // Записываем новое значение Votes в строку комментария по id
            XD::update(['comments'])->set(['comment_votes'], '=', $votes)->where(['comment_id'], '=', $comm_id)->run();
 
            return false;
        } 
    }
 
}