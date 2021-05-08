<?php

namespace App\Controllers;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use Lori\Base;

class FlowController extends \MainController
{
    // Страница потока (flow_action_id):
    // 1 - сообщение (сюда не выводятся)
    // 2 - пост
    // 3 - ответ
    // 4 - комментарий
    // 5 - пост в чат
    // 6 - понравился пост
    // 7 - понравился ответ
    
    public function index()
    {
        $account    = \Request::getSession('account');
        $user_id    = $account ? $account['user_id'] : 0;
        
        Base::Meta(lang('Flow'), lang('Flow'), $other = false);
    
        Request::getResources()->addBottomStyles('/assets/css/flow.css');
        Request::getResources()->addBottomScript('/assets/js/flow.js');
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Flow'),
            'canonical'     => '/flow',
        ];

        return view(PR_VIEW_DIR . '/flow/index', ['data' => $data, 'uid' => $uid]);
    }
    public function contentChat() {
        
        $flows      = FlowModel::getFlowAll();
        
        $result = Array();
        foreach($flows as $ind => $row){
            $row['flow_pubdate']    = Base::ru_date($row['flow_pubdate']);
            $result[$ind]           = $row;
        } 
        
        $uid  = Base::getUid();
        
        return view(PR_VIEW_DIR . '/flow/content', ['uid' => $uid, 'flows' => $result]);
        
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
        
        // Добавим в чат и в поток
        $data_flow = [
            'flow_action_id'    => 5, // чат
            'flow_content'      => $chat_content,
            'flow_user_id'      => $user_id,
            'flow_pubdate'      => date("Y-m-d H:i:s"),
            'flow_url'          => 0,
            'flow_target_id'    => 0,
            'flow_about'        => lang('add_chat'),
            'flow_space_id'     => 0,
            'flow_tl'           => 0,
            'flow_ip'           => $flow_ip, 
        ];
        FlowModel::FlowAdd($data_flow);
        
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
        
        FlowModel::FlowDelete($flow_id);
       
        return true;
    }
    
}
