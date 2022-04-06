<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{PostModel, AnswerModel, CommentModel, SubscriptionModel, FeedModel, FacetModel};
use Content, Config, Tpl, Html, Meta, Translate, UserData;

class PostController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Full post
    // Полный пост
    public function index($type)
    {
        $slug  = Request::get('slug');
        $id    = Request::getInt('id');

        $content = self::presence($type, $id, $slug, $this->user);

        // Let's record views 
        // Запишем просмотры
        if (!isset($_SESSION['pagenumbers'])) {
            $_SESSION['pagenumbers'] = [];
        }

        if (!isset($_SESSION['pagenumbers'][$content['post_id']])) {
            PostModel::updateCount($content['post_id'], 'hits');
            $_SESSION['pagenumbers'][$content['post_id']] = $content['post_id'];
        }

        $content['modified'] = $content['post_date'] != $content['post_modified'] ? true : false;

        $facets = PostModel::getPostTopic($content['post_id'], $this->user['id'], 'topic');
        $blog   = PostModel::getPostTopic($content['post_id'], $this->user['id'], 'blog');

        // Show the draft only to the author
        // Покажем черновик только автору
        if ($content['post_draft'] == 1 && $content['post_user_id'] != $this->user['id']) {
            redirect('/');
        }

        // If the post type is a page, then depending on the conditions we make a redirect
        // Если тип поста страница, то в зависимости от условий делаем редирект
        if ($content['post_type'] == 'page' && $id > 0) {
            if ($blog) {
                redirect(getUrlByName('blog.article', ['facet_slug' => $blog[0]['facet_slug'], 'slug' => $content['post_slug']]));
            }
            redirect(getUrlByName('facet.article', ['facet_slug' => 'info', 'slug' => $content['post_slug']]));
        }

        // Q&A (post_feature == 1) or Discussiona
        $content['amount_content'] = $content['post_answers_count'];
        if ($content['post_feature'] == 0) {
            $comment_n = $content['post_comments_count'] + $content['post_answers_count'];
            $content['amount_content'] = $comment_n;
        }

        $post_answers = AnswerModel::getAnswersPost($content['post_id'], $this->user['id'], $content['post_feature']);

        $answers = [];
        foreach ($post_answers as $ind => $row) {

            if (strtotime($row['answer_modified']) < strtotime($row['date'])) {
                $row['edit'] = 1;
            }
            // TODO: N+1 см. AnswerModel()
            $row['comments'] = CommentModel::getComments($row['answer_id'], $this->user['id']);
            $answers[$ind]   = $row;
        }

        $content_img  = Config::get('meta.img_path');
        if ($content['post_content_img']) {
            $content_img  = PATH_POSTS_COVER . $content['post_content_img'];
        } elseif ($content['post_thumb_img']) {
            $content_img  = PATH_POSTS_THUMB . $content['post_thumb_img'];
        }

        $description  = explode("\n", $content['post_content']);
        $description  = strip_tags($description[0]);
        if ($description == '') {
            $description = strip_tags($content['post_title']);
        }

        if ($content['post_is_deleted'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        Request::getResources()->addBottomStyles('/assets/js/prism/prism.css');
        Request::getResources()->addBottomScript('/assets/js/prism/prism.js');
        Request::getResources()->addBottomScript('/assets/js/zoom/medium-zoom.min.js');

        if ($this->user['id'] > 0 && $content['post_closed'] == 0) {
            Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
            Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');
        }

        if ($content['post_related']) {
            $related_posts = PostModel::postRelated($content['post_related']);
        }

        $m = [
            'date'      => $content['post_date'],
            'og'        => true,
            'imgurl'    => $content_img,
            'url'       => getUrlByName('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]),
        ];

        $view = $type == 'post' ? '/post/view' : '/page/view';

        if ($type == 'post') {
            return Tpl::agRender(
                '/post/view',
                [
                    'meta'  => Meta::get(strip_tags($content['post_title']), $description, $m),
                    'data'  => [
                        'post'          => $content,
                        'answers'       => $answers,
                        'recommend'     => PostModel::postsSimilar($content['post_id'], $this->user, 5),
                        'related_posts' => $related_posts ?? '',
                        'post_signed'   => SubscriptionModel::getFocus($content['post_id'], $this->user['id'], 'post'),
                        'facets'        => $facets,
                        'blog'          => $blog ?? null,
                        'last_user'     => PostModel::getPostLastUser($content['post_id']),
                        'sheet'         => 'article',
                        'type'          => 'post',
                    ]
                ]
            );
        }

        $slug_facet = Request::get('facet_slug');
        $type_facet = $type == 'info.page' ? 'section' : 'blog';

        $facet  = FacetModel::getFacet($slug_facet, 'slug', $type_facet);
        Html::pageError404($facet);

        $m = [
            'og'    => false,
            'url'   => getUrlByName('page', ['facet' => $content['post_slug'], 'slug' => $facet['facet_slug']]),
        ];

        $title = $content['post_title'] . ' - ' . Translate::get('page');
        return Tpl::agRender(
            '/page/view',
            [
                'meta'  => Meta::get($title, $description . ' (' . $facet['facet_title'] . ' - ' . Translate::get('page') . ')', $m),
                'data'  => [
                    'sheet' => 'page',
                    'type'  => $type,
                    'page'  => $content,
                    'facet' => $facet,
                    'pages' => PostModel::recent($facet['facet_id'], $content['post_id'])
                ]
            ]
        );
    }

    public static function presence($type, $id, $slug, $user)
    {
        // Check id and get content data
        // Проверим id и получим данные контента
        if ($type == 'post') {
            $content = PostModel::getPost($id, 'id', $user);

            // If the post slug is different from the data in the database
            // Если slug поста отличается от данных в базе
            if ($slug != $content['post_slug']) {
                redirect(getUrlByName('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]));
            }

            // Redirect when merging a post
            // Редирект при слиянии поста
            if ($content['post_merged_id'] > 0 && !UserData::checkAdmin()) {
                redirect('/post/' . $content['post_merged_id']);
            }
        } else {
            $content  = PostModel::getPost($slug, 'slug', $user);
        }

        Html::pageError404($content);

        return $content;
    }

    // Posting your post on your profile
    // Размещение своего поста у себя в профиле
    public function postProfile()
    {
        $post_id    = Request::getPostInt('post_id');
        $post       = PostModel::getPost($post_id, 'id', $this->user);

        // Access check
        // Проверка доступа
        if (!Html::accessСheck($post, 'post', $this->user, 0, 0)) {
            redirect('/');
        }

        // Prohibit adding a draft to the profile
        // Запретим добавлять черновик в профиль
        if ($post['post_draft'] == 1) {
            return false;
        }

        return PostModel::setPostProfile($post_id, $this->user['id']);
    }

    // View post from cover page
    // Просмотр поста с титульной страницы
    public function shownPost()
    {
        $post_id = Request::getPostInt('post_id');
        $post    = PostModel::getPost($post_id, 'id', $this->user);

        $post['post_content'] = Content::text($post['post_content'], 'text');

        Tpl::agIncludeTemplate('/content/post/postcode', ['post' => $post, 'user'   => $this->user]);
    }

    // Posts by domain
    // Посты по домену
    public function domain($sheet, $type)
    {
        $domain     = Request::get('domain');
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $site       = PostModel::getDomain($domain, $this->user['id']);
        Html::pageError404($site);

        $site['item_content'] = Content::text($site['item_content'], 'line');

        $posts      = FeedModel::feed($page, $this->limit, $this->user, $sheet, $site['item_domain']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $site['item_domain']);

        $m = [
            'og'    => false,
            'url'   => getUrlByName('domain', ['domain' => $domain]),
        ];

        return Tpl::agRender(
            '/post/link',
            [
                'meta'  => Meta::get(Translate::get('domain') . ': ' . $domain, Translate::get('domain.desc') . ': ' . $domain, $m),
                'data'  => [
                    'sheet'         => 'domain',
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'posts'         => $posts,
                    'domains'       => PostModel::getDomainTop($domain),
                    'site'          => $site,
                    'type'          => $type,
                ]
            ]
        );
    }

    // Last 5 pages by content id
    // Последние 5 страниц по id контенту
    public function last($content_id)
    {
        return PostModel::recent($content_id, null);
    }
}
