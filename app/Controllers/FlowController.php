<?php

namespace App\Controllers;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use Base;

class FlowController extends \MainController
{
    // Страница потока (flow_action_id):
    // 1 - add post
    // 2 - add comment
    // 3 - up post
    // 4 - up comment
    // 5 - chat
    public function index()
    {
        $account    = \Request::getSession('account');
        $user_id    = $account ? $account['user_id'] : 0;

        $flows      = FlowModel::getFlowAll();

        $result = Array();
        foreach($flows as $ind => $row){
             
            if(!$row['avatar']) {
                $row['avatar'] = 'noavatar.png';
            } 

            $row['avatar']          = $row['avatar'];
            $row['flow_pubdate']    = Base::ru_date($row['flow_pubdate']);
            $result[$ind]           = $row;
         
        } 

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Flow'),
            'title'         => lang('Flow'). ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Лента потока, активности на сайте ' . $GLOBALS['conf']['sitename'],
        ];

        return view("flow/index", ['data' => $data, 'uid' => $uid, 'flows' => $result]);
    }

    // Добавим чат
    public function chatAdd() 
    {
        $account        = \Request::getSession('account');
        $user_id        = $account ? $account['user_id'] : 0;
        $flow_ip        = \Request::getRemoteAddress();
        $chat_content   = \Request::getPost('flow');
        
        // Проверяем длину тела
        if (Base::getStrlen($chat_content) < 6 || Base::getStrlen($chat_content) > 500)
        {
            Base::addMsg('Длина поста должна быть от 6 до 500 знаков', 'error');
            redirect('/flow');
            return true;
        }
        
        $data = [
            'flow_action_id'    => 5,
            'flow_content'      => $chat_content,
            'flow_user_id'      => $user_id,
            'flow_ip'           => $flow_ip,
        ];
        
        // Записываем пост
        FlowModel::FlowChatAdd($data);
        
        redirect('/flow');   
    }
    
    // Удаляем  / восстанавливаем
    public function deleteFlow()
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $flow_id = \Request::getPostInt('flow_id');
        
        FlowModel::FlowDelete($post_id);
       
        return true;
    }
    
}
