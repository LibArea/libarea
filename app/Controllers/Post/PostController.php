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

        $content = self::presence($type, $id, $slug);

        $this->setPostView($content['post_id'], $this->user['id']);

        $content['modified'] = $content['post_date'] != $content['post_modified'] ? true : false;

        $facets = PostModel::getPostTopic($content['post_id'], 'topic');
        $blog   = PostModel::getPostTopic($content['post_id'], 'blog');

        // Show the draft only to the author
        if ($content['post_draft'] == 1 && $content['post_user_id'] != $this->user['id']) {
            redirect('/');
        }

        // If the post type is a page, then depending on the conditions we make a redirect
        if ($content['post_type'] == 'page' && $id > 0) {
            redirect(url('facet.article', ['facet_slug' => 'info', 'slug' => $content['post_slug']]));
        }

        // Q&A (post_feature == 1) or Discussiona
        $content['amount_content'] = ($content['post_feature'] == 0) ? $content['post_comments_count'] + $content['post_answers_count'] : $content['post_answers_count'];

        // Get replies and comments on the post
        $answers = $this->answersPost($content['post_id'], $content['post_feature'], $sorting  = Request::getGet('sort'));

        $description  = (fragment($content['post_content'], 250) == '') ? strip_tags($content['post_title']) : fragment($content['post_content'], 250);

        $this->stylesHead($content, $blog);

        if ($content['post_related']) {
            $related_posts = PostModel::postRelated($content['post_related']);
        }

        $m = [
            'published_time' => $content['post_date'],
            'type'      => 'article',
            'og'        => true,
            'imgurl'    => self::images($content),
            'url'       => post_slug($content['post_id'], $content['post_slug']),
        ];

        // Sending Last-Modified and handling HTTP_IF_MODIFIED_SINCE
        $this->getDataModified($content['post_modified']);

        if ($type == 'post') {
            return $this->render(
                '/post/post-view',
                [
                    'meta'  => Meta::get(strip_tags($content['post_title']), $description, $m),
                    'data'  => [
                        'post'          => $content,
                        'answers'       => $answers,
                        'recommend'     => PostModel::postSimilars($content['post_id'], $facets[0]['facet_id'] ?? null),
                        'related_posts' => $related_posts ?? '',
                        'post_signed'   => SubscriptionModel::getFocus($content['post_id'], 'post'),
                        'facet_signed'  => SubscriptionModel::getFocus($blog[0]['facet_id'] ?? null, 'facet'),
                        'facets'        => $facets,
                        'united'        => PostModel::getPostMerged($content['post_id']),
                        'blog'          => $blog ?? null,
                        'sorting'       => $sorting ?? null,
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

        return $this->render(
            '/post/page-view',
            [
                'meta'  => Meta::get($content['post_title'] . ' - ' . __('app.page'), $description . ' (' . $facet['facet_title'] . ' - ' . __('app.page') . ')', $m),
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

    public function stylesHead($content, $blog)
    {
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
    }

    // Get replies and comments on the post
    // Получим ответы и комментарии на пост
    public function answersPost($post_id, $post_feature, $sorting)
    {
        $post_answers = AnswerModel::getAnswersPost($post_id, $post_feature, $sorting);

        $answers = [];
        foreach ($post_answers as $ind => $row) {

            if (strtotime($row['answer_modified']) < strtotime($row['answer_date'])) {
                $row['edit'] = 1;
            }
            // TODO: N+1 см. AnswerModel()
            $row['comments'] = CommentModel::getCommentsAnswer($row['answer_id']);
            $answers[$ind]   = $row;
        }

        return $answers;
    }

    public static function presence($type, $id, $slug)
    {
        // Check id and get content data
        // Проверим id и получим данные контента
        if ($type == 'post') {
            $content = PostPresence::index($id, 'id');

            // If the post slug is different from the data in the database
            // Если slug поста отличается от данных в базе
            if (config('meta.slug_post') == true) {
                if ($slug != $content['post_slug']) {
                    redirect(post_slug($content['post_id'], $content['post_slug']));
                }
            }

            // Redirect when merging a post
            // Редирект при слиянии поста
            if ($content['post_merged_id'] > 0 && !UserData::checkAdmin()) {
                redirect(url('post_id', ['id' => $content['post_merged_id']]));
            }

            return $content;
        }

        return PostPresence::index($slug, 'slug');
    }

    // Posting your post on your profile
    // Размещение своего поста у себя в профиле
    public function postProfile()
    {
        $post = PostPresence::index($post_id = Request::getPostInt('post_id'), 'id');

        // Access check
        // Проверка доступа
        if ($post['post_user_id'] != UserData::getUserId()) {
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
    public function domain()
    {
        $site = PostModel::availabilityDomain($domain = Request::get('domain'));
        notEmptyOrView404($site);

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, 'web.feed', $domain);
        $pagesCount = FeedModel::feedCount('web.feed', $domain);

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
                    'count'         => $pagesCount,
                    'list'          => PostModel::listDomain($domain),
                    'site'          => $domain,
                    'type'          => 'domain',
                ]
            ]
        );
    }

    // Define an image for meta tags
    // Определим изображение для meta- тегов
    public static function images($content)
    {
        $content_img  = config('meta.img_path');

        if ($content['post_content_img']) {
            $content_img  = Img::PATH['posts_cover'] . $content['post_content_img'];
        } elseif ($content['post_thumb_img']) {
            $content_img  = Img::PATH['posts_thumb'] . $content['post_thumb_img'];
        }

        return $content_img;
    }

    // Last 5 pages by content id
    // Последние 5 страниц по id контенту
    public function last($content_id)
    {
        return PostModel::recent($content_id, null);
    }
}
