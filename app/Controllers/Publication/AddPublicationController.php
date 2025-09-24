<?php

declare(strict_types=1);

namespace App\Controllers\Publication;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{SubscriptionModel, ActionModel, PublicationModel, FacetModel, PollModel, NotificationModel};
use App\Content\Integration\{Discord, Telegram};
use App\Content\Сheck\{Validator, Availability};
use UploadImage, URLScraper, Meta, Msg;

use Utopia\Domains\Domain;

use App\Traits\Poll;
use App\Traits\Slug;
use App\Traits\Related;
use App\Traits\AddFacetsContent;

class AddPublicationController extends Controller
{
    use Poll;
    use Slug;
    use Related;
	use AddFacetsContent;

    public function article(): void
    {
        $this->callIndex('article');
    }

    public function question(): void
    {
        $this->callIndex('question');
    }

    public function post(): void
    {
        $this->callIndex('post');
    }

    public function page(): void
    {
        $this->callIndex('page');
    }

    public function note(): void
    {
        $this->callIndex('note');
    }

    /**
     * Form adding a post / page
     * Форма добавление поста / страницы
     *
     * @return void
     */
    public function callIndex(string $type)
    {
        // Adding from page topic / blog
        // Добавление со странице темы / блога
        $facet_id   = Request::param('facet_id')->asInt();

        if ($facet_id) {
            $facet  =  Availability::allFacet($facet_id);
            if ($facet['facet_type'] === 'topic') {
                $topic  = Availability::facet($facet_id, 'id', 'topic');
            } elseif ($facet['facet_type'] === 'blog' && $facet['facet_user_id'] == $this->container->user()->id()) {
                $blog  = Availability::facet($facet_id, 'id', 'blog');
            }
        }

        render(
            '/publications/add/index',
            [
                'meta'    => Meta::get(__('app.' . $type)),
                'data'  => [
                    'topic'         => $topic ?? false,
                    'blog'          => $blog ?? false,
                    'showing-blog'  => array_merge(FacetModel::getTeamFacets('blog'), FacetModel::getFacetsUser('blog')),
                    'post_arr'      => PublicationModel::postRelatedAll(),
                    'type'          => $type,
                    'count_poll'    => PollModel::getUserQuestionsPollsCount(),
                ]
            ]
        );
    }


    public function addArticle(): void
    {
        $this->callAdd('article');
    }

    public function addQuestion(): void
    {
        $this->callAdd('question');
    }

    public function addPost(): void
    {
        $this->callAdd('post');
    }

    public function addPage(): void
    {
        $this->callAdd('page');
    }

    public function addNote(): void
    {
        $this->callAdd('note');
    }


    /**
     * Add post
     * Добавим пост
     *
     * @param string $type
     * @return void
     */
    public function  callAdd(string $type): void
    {
        if ($post_url = Request::post('post_url')->value()) {
            $site = $this->addUrl($post_url);
        }

        $data = Request::getParsedBody();

        // Let's check the stop words, url
        // Проверим стоп слова и url
        $trigger = (new \App\Controllers\AuditController())->prohibitedContent($data['content']);

        $redirect = url($type . '.form.add', endPart: false);
        Validator::publication($data, $type, $redirect);

        // Post cover
        // Обложка поста
        if (!empty($data['images'])) {
            $post_img = UploadImage::coverPost($data['images'], 0, $redirect);
        }


		if ($type != 'post') {
			if (PublicationModel::getSlug($slug = $this->getSlug($data['title']))) {
				$slug = $slug . "-";
			}
		} else {
			$slug = 'post-' . date('d-m-Y');
		}

        $post_related = $this->relatedPost();

        $required_fields = ['translation', 'draft', 'nsfw', 'hidden', 'closed', 'top'];
        foreach ($required_fields as $field) {
            $fields[$field] = (!empty($data[$field]) == 'on') ?  1 : 0;
        }

        $last_id = PublicationModel::create(
            [
                'post_title'            => $data['title'] ?? '',
                'post_content'          => $data['content'],
                'post_content_img'      => $post_img ?? '',
                'post_thumb_img'        => $site['og_img'] ?? '',
                'post_related'          => $post_related  ?? '',
                'post_slug'             => $slug,
                'post_type'             => $type,
                'post_translation'      => $fields['translation'],
                'post_draft'            => $fields['draft'],
                'post_nsfw'             => $fields['nsfw'],
                'post_hidden'           => $fields['hidden'],
                'post_ip'               => Request::getUri()->getIp(),
                'post_published'        => ($trigger === false) ? 0 : 1,
                'post_user_id'          => $this->container->user()->id(),
                'post_url'              => $post_url ?? '',
                'post_url_domain'       => $site['post_url_domain'] ?? '',
                'post_tl'               => $data['content_tl'] ?? 0,
                'post_closed'           => $fields['closed'],
                'post_top'              => $fields['top'],
                'post_poll'             => $this->selectPoll(Request::post('poll_id')->value() ?? ''),
            ]
        );

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('post', $last_id, post_slug($type, $last_id, $slug));
        }

        $url_content = post_slug($type, $last_id, $slug);
        if ($type === 'page') {
            $url_content = url('admin.facets.all');
        }
		
        // Add fastes (blogs, topics) to the content
		$this->addFacets($data, $last_id, $url_content);

        // Contact via @
        // Обращение через @
        if ($message = \App\Content\Parser\Content::parseUsers($data['content'], true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_POST, $message, $url_content);
        }

        $this->addIntegration($data['content'], $url_content, $data);

        SubscriptionModel::focus($last_id, 'post');

        ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => $type,
                'action_name'   => 'added',
                'url_content'   => $url_content,
            ]
        );

        Msg::redirect(__('msg.post_added'), 'success', $url_content);
    }

    /**
     * Since this is for the post, we will get a preview and analysis of the domain...
     *
     * @param string $post_url
     * @return array
     * @throws \Exception
     */
    public function addUrl(string $post_url)
    {
        $domain = new Domain(host($post_url));

        $site = [
            'og_img'            => self::grabOgImg($post_url),
            'post_url_domain'   => $domain->getRegisterable(),
        ];

        return $site;
    }

    /**
     * Parsing
     * Парсинг
     *
     * @return void
     */
    public function grabMeta()
    {
        $url    = Request::post('uri')->value();
        $result = URLScraper::get($url);

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * Getting Open Graph Protocol Data
     * Получаем данные Open Graph Protocol
     *
     * @param string $post_url
     * @return false|string
     */
    public static function grabOgImg(string $post_url)
    {
        $meta = URLScraper::get($post_url);

        return UploadImage::thumbPost($meta['image']);
    }

    /**
     * Recommend post
     * Рекомендовать пост
     */
    public function recommend(): bool
    {
        $post_id = Request::post('post_id')->asInt();

        if (!$this->container->user()->admin()) {
            return false;
        }

        $post = Availability::content($post_id);

        ActionModel::setRecommend($post_id, $post['post_is_recommend']);

        return true;
    }

    public function addIntegration(string $content, string $url_content, array $fields)
    {
        $post_draft = $fields['post_draft'] ?? false;

        if ($fields['content_tl'] == 0 && $post_draft == 0) {
			
			$title = $fields['title'] ?? __('app.post');

            // Discord
            if (config('integration', 'discord')) {
                Discord::AddWebhook($content, $title, $url_content);
            }

            // Telegram
            if (config('integration', 'telegram')) {
                Telegram::AddWebhook($content, $title, $url_content);
            }
        }
    }
}
