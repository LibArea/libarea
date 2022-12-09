<?php

namespace App\Controllers\Post;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PostPresence;
use App\Services\Сheck\FacetPresence;
use App\Models\{PostModel, AnswerModel, CommentModel, SubscriptionModel, FeedModel};
use Meta, UserData, Access, Img;

use App\Traits\Views;
use App\Traits\LastDataModified;

class PostController extends Controller
{
    use Views;
    use LastDataModified;

    protected $limit = 25;

    // Full post
    // Полный пост
    public function index($type)
    {
        $slug  = Request::get('slug');
        $id    = Request::getInt('id');

        $content = self::presence($type, $id, $slug, $this->user);

        $this->setPostView($content['post_id'], $this->user['id']);

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
            redirect(url('facet.article', ['facet_slug' => 'info', 'slug' => $content['post_slug']]));
        }

        // Q&A (post_feature == 1) or Discussiona
        $content['amount_content'] = $content['post_answers_count'];
        if ($content['post_feature'] == 0) {
            $content['amount_content'] = $content['post_comments_count'] + $content['post_answers_count'];
        }

        $post_answers = AnswerModel::getAnswersPost($content['post_id'], $this->user['id'], $content['post_feature']);

        $answers = [];
        foreach ($post_answers as $ind => $row) {

            if (strtotime($row['answer_modified']) < strtotime($row['answer_date'])) {
                $row['edit'] = 1;
            }
            // TODO: N+1 см. AnswerModel()
            $row['comments'] = CommentModel::getCommentsAnswer($row['answer_id'], $this->user['id']);
            $answers[$ind]   = $row;
        }

        $content_img  = config('meta.img_path');
        if ($content['post_content_img']) {
            $content_img  = Img::PATH['posts_cover'] . $content['post_content_img'];
        } elseif ($content['post_thumb_img']) {
            $content_img  = Img::PATH['posts_thumb'] . $content['post_thumb_img'];
        }

        $description  = fragment($content['post_content'], 250);
        if ($description == '') {
            $description = strip_tags($content['post_title']);
        }

        if ($content['post_is_deleted'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        Request::getResources()->addBottomStyles('/assets/css/share.css');
        Request::getResources()->addBottomScript('/assets/js/share/goodshare.min.js');

        if ($this->user['id'] > 0 && $content['post_closed'] == 0) {
            Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
            Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');
            Request::getResources()->addBottomScript('/assets/js/dialog.js');
        }

        if ($content['post_related']) {
            $related_posts = PostModel::postRelated($content['post_related']);
        }

        $m = [
            'published_time' => $content['post_date'],
            'type'      => 'article',
            'og'        => true,
            'imgurl'    => $content_img,
            'url'       => url('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]),
        ];

        // Отправка Last-Modified и обработка HTTP_IF_MODIFIED_SINCE
        $this->getDataModified($content['post_modified']);

        if ($type == 'post') {
            return $this->render(
                '/post/post-view',
                [
                    'meta'  => Meta::get(strip_tags($content['post_title']), $description, $m),
                    'data'  => [
                        'post'          => $content,
                        'answers'       => $answers,
                        'recommend'     => PostModel::postSimilars($content['post_id'], $this->user, $facets[0]['facet_id'] ?? null),
                        'related_posts' => $related_posts ?? '',
                        'post_signed'   => SubscriptionModel::getFocus($content['post_id'], 'post'),
                        'facets'        => $facets,
                        'united'        => PostModel::getPostMerged($content['post_id']),
                        'blog'          => $blog ?? null,
                        'sheet'         => 'article',
                        'type'          => 'post',
                    ]
                ]
            );
        }

        $slug_facet = Request::get('facet_slug');

        $facet  = FacetPresence::index($slug_facet, 'slug', 'section');

        $m = [
            'og'    => false,
            'url'   => url('facet.article', ['facet_slug' => $facet['facet_slug'], 'slug' => $content['post_slug']]),
        ];

        $title = $content['post_title'] . ' - ' . __('app.page');
        return $this->render(
            '/post/page-view',
            [
                'meta'  => Meta::get($title, $description . ' (' . $facet['facet_title'] . ' - ' . __('app.page') . ')', $m),
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
            $content = PostPresence::index($id, 'id');

            // If the post slug is different from the data in the database
            // Если slug поста отличается от данных в базе
            if ($slug != $content['post_slug']) {
                redirect(url('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]));
            }

            // Redirect when merging a post
            // Редирект при слиянии поста
            if ($content['post_merged_id'] > 0 && !UserData::checkAdmin()) {
                redirect('/post/' . $content['post_merged_id']);
            }

            return $content;
        }

        return PostPresence::index($slug, 'slug');
    }

    // Posting your post on your profile
    // Размещение своего поста у себя в профиле
    public function postProfile()
    {
        $post   = PostPresence::index($post_id = Request::getPostInt('post_id'), 'id');

        // Access check
        // Проверка доступа
        if (Access::author('post', $post, 0) == false) {
            redirect('/');
        }

        // Prohibit adding a draft to the profile
        // Запретим добавлять черновик в профиль
        if ($post['post_draft'] == 1) {
            return false;
        }

        return PostModel::setPostProfile($post_id, $this->user['id']);
    }

    // Posts by domain
    // Посты по домену
    public function domain($sheet)
    {
        $domain     = Request::get('domain');

        $site       = PostModel::getDomain($domain, $this->user['id']);
        notEmptyOrView404($site);

        $site['item_content'] = markdown($site['item_content'], 'line');

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, $this->user, $sheet, $site['item_domain']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $site['item_domain']);

        $m = [
            'og'    => false,
            'url'   => url('domain', ['domain' => $domain]),
        ];

        return $this->render(
            '/post/link',
            [
                'meta'  => Meta::get(__('app.domain') . ': ' . $domain, __('meta.domain_desc') . ': ' . $domain, $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'posts'         => $posts,
                    'domains'       => PostModel::getDomainTop($domain),
                    'site'          => $site,
                    'type'          => 'domain',
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
