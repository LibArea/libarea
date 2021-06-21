<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\FlowModel;
use App\Models\VotesCommentModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;
use Lori\Base;

class VotesCommController extends \MainController
{

   // Голосование за комментарий
    public function votes()
    {
        $comment_id = \Request::getPostInt('comm_id');
        $uid        = Base::getUid();
        
        // Информация об комментарии
        $comm_info = VotesCommentModel::infoCommentId($comment_id);
        
        // Пользователь не должен голосовать за свой комментарий
        if ($uid['id'] == $comm_info['comment_user_id']) {
           return false;
        }    
                      
        // Проверяем, голосовал ли пользователь за комментарий
        $userup = VotesCommentModel::getVoteStatus($comm_info['comment_id'], $uid['id']);   
        
        if($userup == 1) {
            return false;
        } 

        $up = 1;
        $date = date("Y-m-d H:i:s");
        $ip = Request::getRemoteAddress();
        
        VotesCommentModel::saveVoteUp($comment_id, $up, $ip, $uid['id'], $date);
     
        VotesCommentModel::saveVoteComment($comment_id);
        
        return true;
    }
 
}