<?php

declare(strict_types=1);

namespace App\Controllers\Publication;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\{Validator, Availability};
use App\Models\User\UserModel;
use App\Models\{FacetModel, PublicationModel, PollModel};
use UploadImage, Meta, Msg;

use App\Traits\Slug;
use App\Traits\Poll;
use App\Traits\Author;
use App\Traits\Related;
use App\Traits\AddFacetsContent;

class EditPublicationController extends Controller
{
	use Slug;
	use Poll;
	use Author;
	use Related;
	use AddFacetsContent;

	/**
	 * Post edit form
	 * Форма редактирования post
	 *
	 * @return void
	 */
	public function index()
	{
		$content = Availability::content(Request::param('id')->asPositiveInt(), 'id');

		$post_related = [];
		if ($content['post_related']) {
			$post_related = PublicationModel::postRelated($content['post_related']);
		}

		$blog = FacetModel::getFacetsUser('blog');
		$this->checkingEditPermissions($content, $blog);

		render(
			'/publications/edit/index',
			[
				'meta'  => Meta::get(__('app.edit_' . $content['post_type'])),
				'data'  => [
					'sheet'         => 'edit-content',
					'content'		=> $content,
					'user'          => UserModel::get($content['post_user_id'], 'id'),
					'blog'          => $blog,
					'post_arr'      => $post_related,
					'topic_arr'     => PublicationModel::getPostFacet($content['post_id'], 'topic'),
					'blog_arr'      => PublicationModel::getPostFacet($content['post_id'], 'blog'),
					'section_arr'   => PublicationModel::getPostFacet($content['post_id'], 'section'),
					'poll'          => PollModel::getQuestion($content['post_poll']),
				]
			]
		);
	}

    public function editArticle(): void
    {
        $this->callEdit('article');
    }

    public function editQuestion(): void
    {
        $this->callEdit('question');
    }

    public function editPost(): void
    {
        $this->callEdit('post');
    }

    public function editNote(): void
    {
        $this->callEdit('note');
    }

    public function editPage(): void
    {
        $this->callEdit('page');
    }

	public function callEdit(string $type): void
	{
		$data = Request::getParsedBody();
		$post = Availability::content($data['id']);

        if ($type === 'page') {
			if (!$this->container->user()->admin()) {
				redirect('/');
			}
        }

		$post_draft = $data['post_draft'] ?? false === 'on' ? 1 : 0;

		$blog = FacetModel::getFacetsUser('blog');
		$this->checkingEditPermissions($post, $blog);

		$redirect = url('publication.form.edit', ['id' => $data['id']]);

		Validator::publication($data, $type, $redirect);

		$post_date = ($post['post_draft'] == 1 && $post_draft == 0) ? date("Y-m-d H:i:s") : $post['post_date'];

		// Post cover
		$post_img = $post['post_content_img'];
		if (!empty($data['images'])) {
			$post_img = UploadImage::coverPost($data['images'], $post, $redirect);
		}

		// Add fastes (blogs, topics) to the content
		$this->addFacets($data, (int)$data['id'], $redirect);

		$post_related = $this->relatedPost();

		if ($this->container->user()->admin()) {
			$post_merged_id = Request::post('post_merged_id')->asInt();
			$post_slug = Request::post('post_slug')->value();
			if ($post_slug != $post['post_slug']) {
				if (PublicationModel::getSlug($slug = $this->getSlug($post_slug))) {
					$slug = $slug . "-";
				}
			}
		}

		PublicationModel::editPost([
			'post_id' 			=> $data['id'],
			'post_title' 		=> $data['title'] ?? '',
			'post_slug' 		=> $slug ?? $post['post_slug'],
			'post_type' 		=> $type,
			'post_translation'	=> Request::post('translation')->value() == 'on' ? 1 : 0,
			'post_date' 		=> $post_date,
			'post_user_id' 		=> $this->selectAuthor($post['post_user_id'], Request::post('user_id')->value()),
			'post_draft' 		=> $post_draft,
			'post_content' 		=> $data['content'],
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

		Msg::redirect(__('msg.change_saved'), 'success', url('post.id', ['id' => $data['id']]));
	}

	/**
	 * Cover Removal
	 *
	 * @return void
	 */
	function coverPostRemove()
	{
		$post = Availability::content(Request::param('id')->asPositiveInt(), 'id');

		// Удалять может только автор
		// Only the author can delete it
		if ($this->container->access()->author('post', $post) == false) {
			Msg::redirect(__('msg.went_wrong'), 'error');
		}

		PublicationModel::setPostImgRemove($post['post_id']);
		UploadImage::coverPostRemove($post['post_content_img']);

		Msg::redirect(__('msg.cover_removed'), 'success', url('publication.form.edit', ['id' => $post['post_id']]));
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
