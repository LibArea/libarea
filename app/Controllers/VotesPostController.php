<?php

namespace App\Controllers;
use App\Models\PostModel;
use App\Models\VotesPostModel;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class VotesPostController extends \MainController
{

   // Голосование за пост
    public function votes()
    {
        $post_id = \Request::getPostInt('post_id');

        // Проверяем
        if (!$post_id) {
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
            return false;
        }
            
        $up = 1;
        $date = date("Y-m-d H:i:s");
        $ip = Request::getRemoteAddress();

        VotesPostModel::saveVote($post_id, $up, $ip, $user_id, $date);
     
        VotesPostModel::saveVotePost($post_id);
     
        // Добавим в чат и в поток
        $data_flow = [
            'flow_action_id'    => 6, // голосование за пост
            'flow_content'      => '',
            'flow_user_id'      => $user_id,
            'flow_pubdate'      => $date,
            'flow_url'          => '', 
            'flow_target_id'    => $post_id,
            'flow_about'        => lang('add_up_post'),
            'flow_space_id'     => 0,
            'flow_tl'           => 0,
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);

        return true;
        
    }
 
}