<?php

namespace App\Controllers\Topic;

use Hleb\Constructor\Handlers\Request;
use App\Models\TopicModel;
use Lori\Base;

class AddTopicController extends \MainController
{
    // Добавим topic
    public function index() 
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $topic_title        = \Request::getPost('topic_title');
        $topic_description  = \Request::getPost('topic_description');
        $topic_slug         = \Request::getPost('topic_slug');
        $topic_seo_title    = \Request::getPost('topic_seo_title');
        $topic_merged_id    = \Request::getPost('topic_merged_id');
        $topic_related      = \Request::getPost('topic_related');
        
        $redirect = '/topic/add';

        Base::Limits($topic_title , lang('Title'), '3', '64', $redirect);
        Base::Limits($topic_description, lang('Meta Description'), '44', '225', $redirect);
        Base::Limits($topic_slug, lang('Slug'), '3', '43', $redirect);
        Base::Limits($topic_seo_title, lang('Slug'), '4', '225', $redirect);
        
        $topic_merged_id    = empty($topic_merged_id) ? 0 : $topic_merged_id;
        $topic_related      = empty($topic_related) ? '' : $topic_related;
        $topic_img          = 'topic-default.png';
        
        $topic_add_date = date("Y-m-d H:i:s");
        $data = [
            'topic_title'       => $topic_title, 
            'topic_description' => $topic_description, 
            'topic_slug'        => $topic_slug, 
            'topic_img'         => $topic_img,  
            'topic_add_date'    => $topic_add_date,
            'topic_seo_title'   => $topic_seo_title, 
            'topic_merged_id'   => $topic_merged_id,
            'topic_related'     => $topic_related,
            'topic_count'       => 0, 
        ];
        
        $topic_id = TopicModel::add($data);
        
        redirect('/topic/edit/' . $topic_id);  
    }
    
    // Форма добавить topic
    public function add() 
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }

        $data = [
            'meta_title'    => lang('Add topic'),
            'sheet'         => 'topics',
        ]; 

        return view(PR_VIEW_DIR . '/topic/add', ['data' => $data, 'uid' => $uid]);
    }

}