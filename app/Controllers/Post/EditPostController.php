<?php

namespace App\Controllers\Post;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PostPresence;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use UploadImage, Meta, Access, UserData;

use App\Traits\Slug;
use App\Traits\Author;
use App\Traits\Related;

use App\Validate\RulesPost;

class EditPostController extends Controller
{
    use Slug;
    use Author;
    use Related;

    // Post edit form
    // Форма редактирования post
    public function index()
    {
        $post = PostPresence::index($post_id = Request::getInt('id'), 'id');

        $post_related = [];
        if ($post['post_related']) {
            $post_related = PostModel::postRelated($post['post_related']);
        }

        $blog = FacetModel::getFacetsUser($this->user['id'], 'blog');
        $this->checkingEditPermissions($post, $blog);

        return $this->render(
            '/post/edit',
            [
                'meta'  => Meta::get(__('app.edit_' . $post['post_type'])),
                'data'  => [
                    'sheet'         => 'edit-post',
                    'type'          => 'edit',
                    'post'          => $post,
                    'user'          => UserModel::getUser($post['post_user_id'], 'id'),
                    'blog'          => $blog,
                    'post_arr'      => $post_related,
                    'topic_arr'     => PostModel::getPostFacet($post['post_id'], 'topic'),
                    'blog_arr'      => PostModel::getPostFacet($post['post_id'], 'blog'),
                    'section_arr'   => PostModel::getPostFacet($post['post_id'], 'section'),
                ]
            ]
        );
    }

    public function change()
    {
        $post = PostPresence::index($post_id = Request::getPostInt('post_id'));

        $content    = $_POST['content']; // for Markdown
        $post_draft = Request::getPost('post_draft') == 'on' ? 1 : 0;
        $draft      = Request::getPost('draft');

        $blog = FacetModel::getFacetsUser($this->user['id'], 'blog');
        $this->checkingEditPermissions($post, $blog);

        $redirect = url('content.edit', ['type' => $post['post_type'], 'id' => $post_id]);

        RulesPost::rules($title = Request::getPost('post_title'), $content, $redirect);

        // Form hacking
        if ($post['post_draft'] == 0) {
            $draft = 0;
        }

        $post_date = $post['post_date'];
        if ($draft == 1 && $post_draft == 0) {
            $post_date = date("Y-m-d H:i:s");
        }

        // Post cover
        if (!empty($_FILES['images']['name'])) {
            $post_img = UploadImage::coverPost($_FILES['images'], $post, $redirect, $this->user['id']);
        }
        $post_img = $post_img ?? $post['post_content_img'];

        // Related topics
        $fields = Request::getPost() ?? [];
        $new_type = self::addFacetsPost($fields, $post_id, $post['post_type'], $redirect);

        $post_related = $this->relatedPost();

        if (UserData::checkAdmin()) {
            $post_merged_id = Request::getPostInt('post_merged_id');
            $post_slug = Request::getPost('post_slug');
            if ($post_slug != $post['post_slug']) {
                if (PostModel::getSlug($slug = $this->getSlug($post_slug))) {
                    $slug = $slug . "-";
                }
            }
        }

        $post_feature = config('general.qa_site_format') === true ? 'on' : Request::getPost('post_feature');

        PostModel::editPost(
            [
                'post_id'               => $post_id,
                'post_title'            => $title,
                'post_slug'             => $slug ?? $post['post_slug'],
                'post_feature'          => $post_feature == 'on' ? 1 : 0,
                'post_type'             => $new_type,
                'post_translation'      => Request::getPost('translation') == 'on' ? 1 : 0,
                'post_date'             => $post_date,
                'post_user_id'          => $this->selectAuthor($post['post_user_id'], Request::getPost('user_id')),
                'post_draft'            => $post_draft,
                'post_content'          => $content,
                'post_content_img'      => $post_img ?? '',
                'post_related'          => $post_related ?? '',
                'post_merged_id'        => $post_merged_id ?? 0,
                'post_tl'               => Request::getPostInt('content_tl'),
                'post_closed'           => Request::getPost('closed') == 'on' ? 1 : 0,
                'post_top'              => Request::getPost('top') == 'on' ? 1 : 0,
            ]
        );

        is_return(__('msg.change_saved'), 'success', url('post_id', ['id' => $post['post_id']]));
    }

    // Add fastes (blogs, topics) to the post 
    public static function addFacetsPost($fields, $content_id, $redirect)
    {
        $new_type = 'post';
        $facets = $fields['facet_select'] ?? false;
        if (!$facets) {
            is_return(__('msg.select_topic'), 'error', $redirect);
        }
        $topics = json_decode($facets, true);

        $section  = $fields['section_select'] ?? false;
        if ($section) {
            $new_type = 'page';
            $OneFacets = json_decode($section, true);
        }

        $blog_post  = $fields['blog_select'] ?? false;
        if ($blog_post) {
            $TwoFacets = json_decode($blog_post, true);
        }

        $GeneralFacets = array_merge($OneFacets ?? [], $TwoFacets ?? []);

        FacetModel::addPostFacets(array_merge($GeneralFacets ?? [], $topics), $content_id);

        return $new_type;
    }

    // Cover Removal
    function imgPostRemove()
    {
        $post = PostPresence::index($post_id = Request::getInt('id'), 'id');

        if (Access::author('post', $post, 30) == false) {
            is_return(__('msg.went_wrong'), 'error');
        }

        PostModel::setPostImgRemove($post['post_id']);
        UploadImage::coverPostRemove($post['post_content_img'], $this->user['id']);

        is_return(__('msg.cover_removed'), 'success', url('content.edit', ['type' => 'post', 'id' => $post['post_id']]));
    }

    public function uploadContentImage()
    {
        $user_id    = $this->user['id'];
        $type       = Request::get('type');
        $id         = Request::getInt('id');

        if (!in_array($type, ['post-telo', 'answer'])) {
            return false;
        }

        $img = $_FILES['image'];
        if ($_FILES['image']['name']) {
            return json_encode(array('data' => array('filePath' => UploadImage::postImg($img, $user_id, $type, $id))));
        }

        return false;
    }

    public function checkingEditPermissions($post, $blog)
    {
        if (empty($blog)) {
            if (Access::postAuthorAndTeam($post, $blog[0]['facet_user_id'] ?? 0) == false) {
                is_return(__('msg.access_denied'), 'error');
            }
        } else {
            if (Access::author('post', $post, config('trust-levels.edit_time_post')) == false) {
                is_return(__('msg.access_denied'), 'error');
            }
        }

        return true;
    }
}
