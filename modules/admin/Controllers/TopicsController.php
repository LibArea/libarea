<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\TopicModel;
use App\Models\PostModel;
use Agouti\{Base, UploadImage, Validation};

class TopicsController extends MainController
{
    private $uid;
    
    public function __construct() 
    {
        $this->uid  = Base::getUid();
    }
    
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 15;
        $pagesCount = TopicModel::getTopicsAllCount();
        $topics     = TopicModel::getTopicsAll($page, $limit);

        $meta = [
            'meta_title'    => lang('Topics'),
            'sheet'         => 'topics',
        ];
        
        $data = [
            'sheet'         => $sheet == 'all' ? 'topics' : $sheet,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'topics'        => $topics,
        ];  
        
        return view('/topic/topics', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Форма добавить topic
    public function addPage()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $meta = [
            'meta_title'    => lang('Add topic'),
            'sheet'         => 'topics',
        ];
        
        return view('/topic/add', ['meta' => $meta, 'uid' => $this->uid, 'data' => []]);
    }

    // Форма редактирования topic
    public function editPage()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $topic_id   = Request::getInt('id');
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

        $meta = [
            'meta_title'        => lang('Edit topic') . ' | ' . $topic['topic_title'],
            'sheet'             => 'topics',
        ];
        
        $data = [
            'topic'             => $topic, 
            'topic_related'     => $topic_related, 
            'topic_parent_id'   => $topic_parent_id, 
            'post_related'      => $post_related,
        ];
        
        return view('/topic/edit', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Edit topic
    public function edit()
    {
        $topic_id           = Request::getPostInt('topic_id');
        $topic_title        = Request::getPost('topic_title');
        $topic_description  = Request::getPost('topic_description');
        $topic_info         = Request::getPost('topic_info');
        $topic_slug         = Request::getPost('topic_slug');
        $topic_seo_title    = Request::getPost('topic_seo_title');
        $topic_merged_id    = Request::getPostInt('topic_merged_id');
        $topic_is_parent    = Request::getPostInt('topic_is_parent');
        $topic_count        = Request::getPostInt('topic_count');

        $topic = TopicModel::getTopic($topic_id, 'id');
        Base::PageError404($topic);

        // Если убираем тему из корневой, то должны очистеть те темы, которые были подтемами
        if ($topic['topic_is_parent'] == 1 && $topic_is_parent == 0) {
            TopicModel::clearBinding($topic['topic_id']);
        }

        $redirect = getUrlByName('admin.topic.edit', ['id' => $topic['topic_id']]);

        Validation::charset_slug($topic_slug, 'Slug (url)', $redirect);
        Validation::Limits($topic_title, lang('Title'), '3', '64', $redirect);
        Validation::Limits($topic_slug, lang('Slug'), '3', '43', $redirect);
        Validation::Limits($topic_seo_title, lang('Name SEO'), '4', '225', $redirect);
        Validation::Limits($topic_description, lang('Meta Description'), '44', '225', $redirect);
        Validation::Limits($topic_info, lang('Info'), '14', '5000', $redirect);

        // Запишем img
        $img = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if ($check_img) {
            UploadImage::img($img, $topic['topic_id'], 'topic');
        }

        $post_fields        = Request::getPost() ?? [];
        $data = [
            'topic_id'              => $topic_id,
            'topic_title'           => $topic_title,
            'topic_description'     => $topic_description,
            'topic_info'            => $topic_info,
            'topic_slug'            => $topic_slug,
            'topic_seo_title'       => $topic_seo_title,
            'topic_parent_id'       => implode(',', $post_fields['topic_parent_id'] ?? ['0']),
            'topic_is_parent'       => $topic_is_parent,
            'topic_post_related'    => implode(',', $post_fields['topic_post_related'] ?? ['0']),
            'topic_related'         => implode(',', $post_fields['topic_related'] ?? ['0']),
            'topic_count'           => $topic_count,
        ];
        
        TopicModel::edit($data);

        addMsg(lang('Changes saved'), 'success');
        
        redirect(getUrlByName('topic', ['slug' => $topic_slug]));
    }

    // Добавим topic
    public function add()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $topic_title        = Request::getPost('topic_title');
        $topic_description  = Request::getPost('topic_description');
        $topic_slug         = Request::getPost('topic_slug');
        $topic_seo_title    = Request::getPost('topic_seo_title');
        $topic_merged_id    = Request::getPost('topic_merged_id');
        $topic_related      = Request::getPost('topic_related');

        $redirect = getUrlByName('admin.topic.add');

        Validation::charset_slug($topic_slug, 'Slug (url)', $redirect);
        Validation::Limits($topic_title, lang('Title'), '3', '64', $redirect);
        Validation::Limits($topic_description, lang('Meta Description'), '44', '225', $redirect);
        Validation::Limits($topic_slug, lang('Slug'), '3', '43', $redirect);
        Validation::Limits($topic_seo_title, lang('Slug'), '4', '225', $redirect);

        $data = [
            'topic_title'       => $topic_title,
            'topic_description' => $topic_description,
            'topic_slug'        => $topic_slug,
            'topic_img'         => 'topic-default.png',
            'topic_add_date'    =>  date("Y-m-d H:i:s"),
            'topic_seo_title'   => $topic_seo_title,
            'topic_merged_id'   => $topic_merged_id ?? 0,
            'topic_related'     => $topic_related ?? 0,
            'topic_count'       => 0,
        ];

        $topic = TopicModel::add($data);

        redirect(getUrlByName('admin.topic.edit', ['id' => $topic['topic_id']]));
    }

    // Обновление
    public static function updateQuantity()
    {
        TopicModel::setUpdateQuantity();

        redirect('/topics');
    }
}
