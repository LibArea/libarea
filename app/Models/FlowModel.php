<?php

namespace App\Models;
use XdORM\XD;

class FlowModel extends \MainModel
{
    public static function getFlowAll()
    {

        $q = XD::select('*')->from(['flow_log']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['flow_user_id'])
                ->where(['flow_is_delete'], '=', 0)
                ->orderBy(['flow_id'])->desc()->limit(15);

        $result = $query->getSelect();

        return $result;
    }
    
    // Добавляем пост из чата 
    public static function FlowChatAdd($data) 
    {
       XD::insertInto(['flow_log'], '(', 
            ['flow_action_id'], ',', 
            ['flow_content'], ',', 
            ['flow_user_id'], ',', 
            ['flow_ip'], ')')->values( '(', 
        
        XD::setList([
            $data['flow_action_id'], 
            $data['flow_content'], 
            $data['flow_user_id'], 
            $data['flow_ip']]), ')' )->run();

        return true; 
    }
    
    // Удаляем / восстанавливаем 
    public static function FlowDelete($flow_id) 
    {
        if(self::isThePostDeleted($flow_id) == 1) {
            XD::update(['flow_log'])->set(['flow_is_delete'], '=', 0)->where(['flow_id'], '=', $flow_id)->run();
        } else {
            XD::update(['flow_log'])->set(['flow_is_delete'], '=', 1)->where(['flow_id'], '=', $flow_id)->run();
        }
        
        return true;
    }
}