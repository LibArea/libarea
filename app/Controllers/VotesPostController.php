<?php

namespace App\Controllers;
use App\Models\PostModel;
use App\Models\VotesPostModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class VotesPostController extends \MainController
{

   // Голосование за пост
    public function votes()
    {
        
        $post_id = \Request::getPostInt('post_id');

        // Проверяем
        if (!$post_id)
        {
            return false;
        }

        // id того, кто голосует за пост
        $account = Request::getSession('account');
        $user_id = $account['user_id'];

        // Информация об комментарии
        $post_info = VotesPostModel::infoPost($post_id);
          
        // Пользователь не должен голосовать за свой пост
        if ($user_id == $post_info['post_user_id']) {
           return false;
        }    
                      
        // Проверяем, голосовал ли пользователь за пост
        $userup = VotesPostModel::getVoteStatus($post_info['post_id'], $user_id);   
        
        if($userup == 1) {
            
            // далее удаление строки в таблице голосования за пост
            // далее уменьшаем на -1 количество 
            // см. код ниже. А пока:
            
            return false;
            
        } else {
            
            $up = 1;
            $dt = date("Y-m-d H:i:s");
            $ip = Request::getRemoteAddress();
            
           
            
            
            VotesPostModel::saveVote($post_id, $up, $ip, $user_id, $dt);
         
            // Получаем количество votes поста    
            $votes_num = $post_info['post_votes'];
            $votes = $votes_num + 1;
          
            // Записываем новое значение Votes в строку поста по id
            XD::update(['posts'])->set(['post_votes'], '=', $votes)->where(['post_id'], '=', $post_id)->run();
 
            return false;
        } 
    }
 
}