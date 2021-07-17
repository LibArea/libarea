<?php

namespace App\Controllers\Topic;

use Hleb\Constructor\Handlers\Request;
use App\Models\TopicModel;
use App\Models\PostModel;
use Lori\UploadImage;
use Lori\Base;

class EditTopicController extends \MainController
{
    // Edit topic
    public function index()
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $topic_id           = \Request::getPostInt('topic_id');
        $topic_title        = \Request::getPost('topic_title');
        $topic_description  = \Request::getPost('topic_description');
        $topic_info         = \Request::getPost('topic_info');
        $topic_slug         = \Request::getPost('topic_slug');
        $topic_seo_title    = \Request::getPost('topic_seo_title');
        $topic_merged_id    = \Request::getPostInt('topic_merged_id');
        $topic_is_parent    = \Request::getPostInt('topic_is_parent');
        $topic_count        = \Request::getPostInt('topic_count');
        
        $topic = TopicModel::getTopic($topic_id, 'id');
        Base::PageError404($topic);
        
        $parent_id          = empty($_POST['topic_parent_id']) ? '' : $_POST['topic_parent_id'];
        $topic_parent_id    = empty($parent_id) ? 0 : implode(',', $parent_id);
        
        $related            = empty($_POST['topic_related']) ? '' : $_POST['topic_related'];
        $topic_related      = empty($related) ? '' : implode(',', $related);
        
        $related_post       = empty($_POST['post_related']) ? '' : $_POST['post_related'];
        $topic_post_related = empty($related_post) ? '' : implode(',', $related_post);
        
        // Если убираем тему из корневой, то должны очистеть те темы, которые были подтемами
        if ($topic['topic_is_parent'] == 1 && $topic_is_parent == 0) {
            TopicModel::clearBinding($topic['topic_id']);
        }

        $redirect = '/topic/edit/' . $topic['topic_id'];

        Base::Limits($topic_title , lang('Title'), '3', '64', $redirect);
        Base::Limits($topic_slug, lang('Slug'), '3', '43', $redirect);
        Base::Limits($topic_seo_title, lang('Name SEO'), '4', '225', $redirect);
        Base::Limits($topic_description, lang('Meta Description'), '44', '225', $redirect);
        Base::Limits($topic_info, lang('Info'), '14', '5000', $redirect);
        
        $topic_merged_id    = empty($topic_merged_id) ? 0 : $topic_merged_id;
        $topic_parent_id    = empty($topic_parent_id) ? 0 : $topic_parent_id;
        $topic_is_parent    = empty($topic_is_parent) ? 0 : $topic_is_parent;
        $topic_count        = empty($topic_count) ? 0 : $topic_count;
        
        // Запишем img
        $img = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if($check_img) {
            UploadImage::img($img, $topic['topic_id'], 'topic');
        }         
         
        $topic_add_date = date("Y-m-d H:i:s");
        $data = [
            'topic_id'              => $topic_id, 
            'topic_title'           => $topic_title, 
            'topic_description'     => $topic_description,
            'topic_info'            => $topic_info, 
            'topic_slug'            => $topic_slug, 
            'topic_add_date'        => $topic_add_date,
            'topic_seo_title'       => $topic_seo_title, 
            'topic_merged_id'       => $topic_merged_id,
            'topic_parent_id'       => $topic_parent_id,
            'topic_is_parent'       => $topic_is_parent,
            'topic_post_related'    => $topic_post_related,
            'topic_related'         => $topic_related,
            'topic_count'           => $topic_count, 
        ];
        
        TopicModel::edit($data);
        
        Base::addMsg(lang('Changes saved'), 'success');
        redirect($redirect);  
    }
    
    // Форма редактирования topic
    public function edit() 
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $topic_id   = \Request::getInt('id');
        $topic      = TopicModel::getTopic($topic_id, 'id');
        
        Base::PageError404($topic);
        
        Request::getResources()->addBottomStyles('/assets/css/select2.css'); 
        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js'); 
        
        $topic_related      = TopicModel::topicRelated($topic['topic_related']);
        $post_related       = PostModel::postRelated($topic['topic_post_related']);
        
        $topic_parent_id    = '';
        if ($topic['topic_parent_id']  != 0) {
            $topic_parent_id    = TopicModel::topicMain($topic['topic_parent_id']);
        } 
        
        $data = [
            'meta_title'    => lang('Edit topic') . ' — ' . $topic['topic_title'],
            'sheet'         => 'topics',
        ]; 

        return view(PR_VIEW_DIR . '/topic/edit', ['data' => $data, 'uid' => $uid, 'topic' => $topic, 'topic_related' => $topic_related, 'topic_parent_id' => $topic_parent_id, 'post_related' => $post_related]);
    }
    
    // Обновление
    public static function updateQuantity()
    {
        TopicModel::setUpdateQuantity();
        
        redirect('/topics');
    }
    
}