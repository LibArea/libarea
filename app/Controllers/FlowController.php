<?php

namespace App\Controllers;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class FlowController extends \MainController
{
  
    public function index()
    {
        Request::getResources()->addBottomStyles('/assets/css/flow.css');
        Request::getResources()->addBottomScript('/assets/js/flow.js');
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Flow'),
            'canonical'     => '/flow',
            'sheet'         => 'flow',
            'meta_title'    => lang('Flow') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Flow') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
 
        return view(PR_VIEW_DIR . '/flow/index', ['data' => $data, 'uid' => $uid]);
    }
    public function contentChat() {
        
        $flows      = FlowModel::getFlowAll();
        
        $result = Array();
        foreach ($flows as $ind => $row) {
            $row['flow_content']    = Content::text($row['flow_content'], 'line');
            $row['flow_pubdate']    = lang_date($row['flow_pubdate']);
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
        Base::Limits($chat_content, lang('Title'), '6', '500', '/flow');
        
        // Добавим в чат и в поток
        $data_flow = [
            'flow_action_type'  => 'add_chat',
            'flow_content'      => $chat_content,
            'flow_user_id'      => $user_id,
            'flow_pubdate'      => date("Y-m-d H:i:s"),
            'flow_url'          => 0,
            'flow_target_id'    => 0,
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
