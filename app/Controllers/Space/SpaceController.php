<?php

namespace App\Controllers\Space;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{SubscriptionModel, SpaceModel, FeedModel};
use Lori\{Content, Config, Base, Validation};

class SpaceController extends MainController
{
    // Все пространства сайта
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 25;
        $pagesCount = SpaceModel::getSpacesAllCount();
        $spaces     = SpaceModel::getSpacesAll($page, $limit, $uid['user_id'], 'all');

        Base::PageError404($spaces);

        // Введем ограничение на количество создаваемых пространств (кроме tl5)
        $sp                 = SpaceModel::getUserCreatedSpaces($uid['user_id']);
        $count_space        = count($sp);
        $total_allowed      = $uid['user_trust_level'] == 5 ? 999 : 3;
        $add_space_button   = Validation::validTl($uid['user_trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, $total_allowed);

        Request::getHead()->addStyles('/assets/css/space.css');

        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/spaces',
            'sheet'         => 'spaces',
            'meta_title'    => lang('All space') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('all-space-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'h1'                => lang('All space'),
            'sheet'             => 'spaces',
            'pagesCount'        => ceil($pagesCount / $limit),
            'pNum'              => $page,
            'spaces'            => $spaces, 
            'add_space_button'  => $add_space_button
        ];

        return view('/space/spaces', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Пространства участника
    public function spaseUser()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $limit  = 25;

        $pagesCount = SpaceModel::getSpacesAllCount();
        $space      = SpaceModel::getSpacesAll($page, $limit, $uid['user_id'], 'subscription');

        // Введем ограничение на количество создаваемых пространств
        $all_space          = SpaceModel::getUserCreatedSpaces($uid['user_id']);
        $count_space        = count($all_space);
        $add_space_button   = Validation::validTl($uid['user_trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, 3);

        Request::getHead()->addStyles('/assets/css/space.css');

        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/space/my',
            'sheet'         => 'my-space',
            'meta_title'    => lang('I read space') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('I read space') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        $data = [
            'h1'                => lang('I read space'),
            'sheet'             => 'my-space',
            'pagesCount'        => ceil($pagesCount / $limit),
            'pNum'              => $page,
            'spaces'            => $space, 
            'add_space_button'  => $add_space_button,
        ];

        return view('/space/spaces', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Посты по пространству
    public function posts($sheet)
    {
        $uid    = Base::getUid();
        $slug   = Request::get('slug');
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $space = SpaceModel::getSpace($slug, 'slug');
        Base::PageError404($space);

        $limit = 25;

        $data = ['space_id' => $space['space_id']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'space', $data);
        $pagesCount = FeedModel::feedCount($uid, 'space', $data);

        $space['space_date']        = lang_date($space['space_date']);
        $space['space_cont_post']   = count($posts);
        $space['space_text']        = Content::text($space['space_text'], 'text');

        $post_result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $post_result[$ind]                   = $row;
        }

        // Отписан участник от пространства или нет
        $space_signed = SubscriptionModel::getFocus($space['space_id'], $uid['user_id'], 'space');

        $num = '';
        if ($page > 1) {
            $num =  sprintf(lang('page-number'), $page);
        }

        $writers = []; 
        if ($sheet == 'feed') {
            $title          = $space['space_name'] . ' — ' . lang('space-feed-title') . '. ' . $num;
            $description    = $space['space_name'] . '. ' . $space['space_description'];
        } elseif ($sheet == 'writers') {
            // Получим список (top 10) популярных авторов
            $writers = SpaceModel::getWriters($space['space_id']);
            $title          = $space['space_name'] . ' — ' . lang('space-writers-title') . '. ' . $num;
            $description    = $title . '. ' . $space['space_description']; 
        } else {
            $title          = $space['space_name'] . ' — ' . lang('space-top-title') . '. ' . $num;
            $description    = $title . '. ' . $space['space_description'];
        }

        Request::getHead()->addStyles('/assets/css/space.css');
        
        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/s/' . $space['space_slug'],
            'img'           => Config::get(Config::PARAM_URL) . '/uploads/spaces/logos/' . $space['space_img'],
            'sheet'         => $sheet,
            'meta_title'    => $title . Config::get(Config::PARAM_NAME),
            'meta_desc'     => $description . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        $data = [
            'h1'            => $space['space_name'],
            'sheet'         => $sheet,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'posts'         => $post_result,
            'space'         => $space, 
            'signed'        => $space_signed, 
            'writers'       => $writers
        ];

        return view('/space/space', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    } 
    
}