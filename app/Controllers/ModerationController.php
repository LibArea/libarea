<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\ModerationModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class ModerationController extends \MainController
{
   
    public function index()
    {
        $moderations_log      = ModerationModel::getModerations();
        
        $result = Array();
        foreach ($moderations_log as $ind => $row) {
            $row['mod_created_at']    = lang_date($row['mod_created_at']);
            $result[$ind]         = $row;
        } 
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Moderation Log'),
            'canonical'     => '/moderations',
            'sheet'         => 'moderations',
            'meta_title'    => lang('Moderation Log') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('meta-moderation') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        return view(PR_VIEW_DIR . '/moderation/index', ['data' => $data, 'uid' => $uid, 'moderations' => $result]);
    }
    
}
