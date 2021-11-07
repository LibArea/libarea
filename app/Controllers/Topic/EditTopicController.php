<?php

namespace App\Controllers\Topic;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{TopicModel, PostModel};
use Base, Validation, UploadImage, Translate;

class EditTopicController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма редактирования topic
    public function index()
    {

        $topic_id   = Request::getInt('id');
        $topic      = TopicModel::getTopic($topic_id, 'id');
        Base::PageError404($topic);

        // Проверка доступа 
        if (!accessСheck($topic, 'topic', $this->uid, 0, 0)) {
            redirect('/');
        }

        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        $topic_related      = TopicModel::topicRelated($topic['topic_related']);
        $post_related       = PostModel::postRelated($topic['topic_post_related']);

        return view(
            '/topic/edit',
            [
                'meta'  => meta($m = [], Translate::get('edit topic') . ' | ' . $topic['topic_title']),
                'uid'   => $this->uid,
                'data'  => [
                    'topic'             => $topic,
                    'topic_related'     => $topic_related,
                    'post_related'      => $post_related,
                    'high_lists'        => TopicModel::getHighLevelList($topic['topic_id']),
                    'low_lists'         => TopicModel::getLowLevelList($topic['topic_id']),
                    'user'              => UserModel::getUser($topic['topic_user_id'], 'id'),
                    'sheet'             => 'topics',
                ]
            ]
        );
    }

    public function edit()
    {
        // Временно запретим участникам
        if ($this->uid['user_trust_level'] != 5) redirect('/');

        $topic_id                   = Request::getPostInt('topic_id');
        $topic_title                = Request::getPost('topic_title');
        $topic_description          = Request::getPost('topic_description');
        $topic_short_description    = Request::getPost('topic_short_description');
        $topic_info                 = Request::getPost('topic_info');
        $topic_slug                 = Request::getPost('topic_slug');
        $topic_seo_title            = Request::getPost('topic_seo_title');
        $topic_merged_id            = Request::getPostInt('topic_merged_id');
        $topic_top_level            = Request::getPostInt('topic_top_level');
        $topic_user_new             = Request::getPost('user_select');
        $topic_tl                   = Request::getPostInt('content_tl');
        $topic_is_web               = Request::getPostInt('topic_is_web');

        $topic = TopicModel::getTopic($topic_id, 'id');
        Base::PageError404($topic);

        // Проверка доступа 
        if (!accessСheck($topic, 'topic', $this->uid, 0, 0)) {
            redirect('/');
        }

        $redirect = getUrlByName('admin.topic.edit', ['id' => $topic['topic_id']]);

        Validation::charset_slug($topic_slug, 'Slug (url)', $redirect);
        Validation::Limits($topic_title, Translate::get('title'), '3', '64', $redirect);
        Validation::Limits($topic_slug, Translate::get('slug'), '3', '43', $redirect);
        Validation::Limits($topic_seo_title, Translate::get('name SEO'), '4', '225', $redirect);
        Validation::Limits($topic_description, Translate::get('meta description'), '44', '225', $redirect);
        Validation::Limits($topic_short_description, Translate::get('short description'), '11', '160', $redirect);
        Validation::Limits($topic_info, Translate::get('Info'), '14', '5000', $redirect);

        // Запишем img
        $img = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if ($check_img) {
            UploadImage::img($img, $topic['topic_id'], 'topic');
        }

        // Если есть смена topic_user_id и это TL5
        $topic_user_id = $topic['topic_user_id'];
        if ($topic['topic_user_id'] != $topic_user_new) {
            $topic_user_id = $topic['topic_user_id'];
            if ($this->uid['user_trust_level'] == 5) {
                $topic_user_id = $topic_user_new;
            }
        }

        $slug = TopicModel::getTopic($topic_slug, 'slug');
        if ($slug['topic_slug'] != $topic['topic_slug']) {
            if ($slug) {
                addMsg(Translate::get('url-already-exists'), 'error');
                redirect(getUrlByName('topic', ['slug' => $topic_slug]));
            }
        }

        $post_fields    = Request::getPost() ?? [];
        $topic_slug     = strtolower($topic_slug);
        $data = [
            'topic_id'                  => $topic_id,
            'topic_title'               => $topic_title,
            'topic_description'         => $topic_description,
            'topic_short_description'   => $topic_short_description,
            'topic_info'                => $topic_info,
            'topic_slug'                => $topic_slug,
            'topic_seo_title'           => $topic_seo_title,
            'topic_user_id'             => $topic_user_id,
            'topic_tl'                  => $topic_tl,
            'topic_top_level'           => $topic_top_level,
            'topic_post_related'        => implode(',', $post_fields['post_related'] ?? ['0']),
            'topic_related'             => implode(',', $post_fields['topic_related'] ?? ['0']),
            'topic_is_web'              => $topic_is_web,
        ];

        TopicModel::edit($data);

        // Тема, выбор родителя   
        $topic_fields   = Request::getPost() ?? [];   
        $topics         =  $topic_fields['topic_parent_id'] ?? [];

        $arr = [];
        foreach ($topics as $row) {
            $arr[] = array($row, $topic_id);
        }
        TopicModel::addTopicRelation($arr, $topic_id);

        addMsg(Translate::get('changes saved'), 'success');

        redirect(getUrlByName('topic', ['slug' => $topic_slug]));
    }
    
    // Выбор родительской темы
    public function selectTopicParent()
    {
        $topic_id = Request::getInt('topic_id');
        $search = Request::getPost('searchTerm');
        $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);

        return TopicModel::getSearchParent($search, $topic_id);
    }
}
