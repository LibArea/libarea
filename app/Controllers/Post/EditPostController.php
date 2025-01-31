<?php

declare(strict_types=1);

namespace App\Controllers\Post;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\PostPresence;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel, PollModel};
use UploadImage, Meta, Msg;

use App\Traits\Slug;
use App\Traits\Poll;
use App\Traits\Author;
use App\Traits\Related;

use App\Validate\RulesPost;

class EditPostController extends Controller
{
	use Slug;
	use Poll;
	use Author;
	use Related;

	/**
	 * Post edit form
	 * Форма редактирования post
	 *
	 * @return void
	 */
	public function index()
	{
		$post = PostPresence::index(Request::param('id')->asPositiveInt(), 'id');

		$post_related = [];
		if ($post['post_related']) {
			$post_related = PostModel::postRelated($post['post_related']);
		}

		$blog = FacetModel::getFacetsUser('blog');
		$this->checkingEditPermissions($post, $blog);

		render(
			'/post/edit',
			[
				'meta'  => Meta::get(__('app.edit_' . $post['post_type'])),
				'data'  => [
					'sheet'         => 'edit-post',
					'type'          => 'edit',
					'post'          => $post,
					'user'          => UserModel::get($post['post_user_id'], 'id'),
					'blog'          => $blog,
					'post_arr'      => $post_related,
					'topic_arr'     => PostModel::getPostFacet($post['post_id'], 'topic'),
					'blog_arr'      => PostModel::getPostFacet($post['post_id'], 'blog'),
					'section_arr'   => PostModel::getPostFacet($post['post_id'], 'section'),
					'poll'          => PollModel::getQuestion($post['post_poll']),
				]
			]
		);
	}

	public function edit(): void
    {
	 	$img = Request::post('images')->value();
		
		$post_id = Request::post('post_id')->asInt();
		$post = PostPresence::index($post_id);

		$content = $_POST['content']; // for Markdown
		$post_draft = Request::post('post_draft')->value() === 'on' ? 1 : 0;

		$blog = FacetModel::getFacetsUser('blog');
		$this->checkingEditPermissions($post, $blog);

		$redirect = url($post['post_type'] . '.form.edit', ['id' => $post_id]);

		RulesPost::rules($title = Request::post('post_title')->value(), $content, $redirect);

		$post_date = ($post['post_draft'] == 1 && $post_draft == 0) ? date("Y-m-d H:i:s") : $post['post_date'];

		// Post cover
		$post_img = $post['post_content_img'];
		if (!empty($img)) {
			$post_img = UploadImage::coverPost($img, $post, $redirect);
		}

		// Related topics
		$fields = Request::allPost() ?? [];
		$new_type = self::addFacetsPost($fields, $post_id, $post['post_type'], $redirect);

		$post_related = $this->relatedPost();

		if ($this->container->user()->admin()) {
			$post_merged_id = Request::post('post_merged_id')->asInt();
			$post_slug = Request::post('post_slug')->value();
			if ($post_slug != $post['post_slug']) {
				if (PostModel::getSlug($slug = $this->getSlug($post_slug))) {
					$slug = $slug . "-";
				}
			}
		}

		$post_feature = config('general', 'qa_site_format') === true ? 'on' : Request::post('post_feature');

		PostModel::editPost([
			'post_id' 			=> $post_id,
			'post_title' 		=> $title,
			'post_slug' 		=> $slug ?? $post['post_slug'],
			'post_feature' 		=> $post_feature == 'on' ? 1 : 0,
			'post_type' 		=> $new_type,
			'post_translation'	=> Request::post('translation')->value() == 'on' ? 1 : 0,
			'post_date' 		=> $post_date,
			'post_user_id' 		=> $this->selectAuthor($post['post_user_id'], Request::post('user_id')->value()),
			'post_draft' 		=> $post_draft,
			'post_content' 		=> $content,
			'post_content_img' 	=> $post_img ?? '',
			'post_related' 		=> $post_related ?? '',
			'post_merged_id' 	=> $post_merged_id ?? 0,
			'post_tl' 			=> Request::post('content_tl')->asInt(),
			'post_closed' 		=> Request::post('closed')->value() == 'on' ? 1 : 0,
			'post_nsfw' 		=> Request::post('nsfw')->value() == 'on' ? 1 : 0,
			'post_hidden' 		=> Request::post('hidden')->value() == 'on' ? 1 : 0,
			'post_top' 			=> Request::post('top')->value() == 'on' ? 1 : 0,
			'post_poll' 		=> $this->selectPoll(Request::post('poll_id')->value()),
			'post_modified' 	=> date("Y-m-d H:i:s"),
		]);

		Msg::redirect(__('msg.change_saved'), 'success', url('post.id', ['id' => $post['post_id']]));
	}

    /**
     * Add fastes (blogs, topics) to the post
     *
     * @param array $fields
     * @param int $content_id
     * @param string $redirect
     * @return string
     */
	public static function addFacetsPost(array $fields, int $content_id, string $redirect): string
    {
		$new_type = 'post';
		$facets = $fields['facet_select'] ?? false;

		if (!$facets) {
			Msg::redirect(__('msg.select_topic'), 'error', $redirect);
		}

		$topics = json_decode($facets, true);

		$section = $fields['section_select'] ?? false;
		$OneFacets = [];

		if ($section) {
			$new_type = 'page';
			$OneFacets = json_decode($section, true);
		}

		$blog_post = $fields['blog_select'] ?? false;
		$TwoFacets = [];

		if ($blog_post) {
			$TwoFacets = json_decode($blog_post, true);
		}

		$GeneralFacets = array_merge($OneFacets, $TwoFacets);

		FacetModel::addPostFacets(array_merge($GeneralFacets, $topics), $content_id);

		return $new_type;
	}

	/**
	 * Cover Removal
	 *
	 * @return void
	 */
	function coverPostRemove()
	{
		$post = PostPresence::index(Request::param('id')->asPositiveInt(), 'id');

		// Удалять может только автор
		// Only the author can delete it
		if ($this->container->access()->author('post', $post) == false) {
			Msg::redirect(__('msg.went_wrong'), 'error');
		}

		PostModel::setPostImgRemove($post['post_id']);
		UploadImage::coverPostRemove($post['post_content_img']);

		Msg::redirect(__('msg.cover_removed'), 'success', url('post.form.edit', ['id' => $post['post_id']]));
	}

	public function uploadContentImage()
	{
		$type       = Request::param('type')->value();
		$id         = Request::param('id')->asInt();

		if (!in_array($type, ['post-telo', 'comment'])) {
			return false;
		}

		$img = $_FILES['file'];
		if ($_FILES['file']['name']) {
			return json_encode(['data' => ['filePath' => UploadImage::postImg($img, $type, $id)]]);
		}

		return false;
	}

	public function checkingEditPermissions(array $post, array $blog)
	{
		if (empty($blog)) {
			if ($this->container->access()->author('post', $post) == false) {
				Msg::redirect(__('msg.access_denied'), 'error');
			}
		} else {
			if ($this->container->access()->postAuthor($post, $blog[0]['facet_user_id'] ?? 0) == false) {
				Msg::redirect(__('msg.access_denied'), 'error');
			}
		}

		return true;
	}
}
