<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\DetectMobile;
use App\Services\Сheck\{PostPresence, CommentPresence};
use App\Models\{NotificationModel, ActionModel, CommentModel, PostModel};
use App\Validate\Validator;
use UserData;

class AddCommentController extends Controller
{
    // Show the form for adding a комментария
    // Покажем форму добавление комментария
    public function index()
    {
        insert('/_block/form/add-comment');
    }
	
    public function create()
    {		
		if ($post_id = Request::getPostInt('post_id')) { 
			$post = PostPresence::index($post_id, 'id');
		}
		
	  	if ($comment_id = Request::getPostInt('comment_id')) { 
			$comment = CommentPresence::index($comment_id);
			$post = PostPresence::index($comment['comment_post_id'], 'id');
		}
	 
        $url_post = post_slug($post['post_id'], $post['post_slug']);
 
        Validator::Length($content = $_POST['content'], 6, 5000, 'content', $url_post);

        // Let's check the stop words, url
        // Проверим стоп слова и url
        $trigger = (new \App\Services\Audit())->prohibitedContent($content);

        $this->joinPost($content, $post, $url_post);
        $this->joinComment($content, $url_post, $comment ?? []);

        $last_id = CommentModel::add($post['post_id'], $comment_id, $content, $trigger, DetectMobile::index());

        // Add an audit entry and an alert to the admin
        // Аудит и оповещение персоналу
        if ($trigger === false) {
            (new \App\Services\Audit())->create('comment', $last_id, url('admin.audits'));
        }

        $url = $url_post . '#comment_' . $last_id;

        $this->notifPost($content, $post, $comment_id, $url);

        ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => 'comment',
                'action_name'   => 'comment',
                'url_content'   => $url,
            ]
        );

        redirect($url);
    }

    public function joinPost($content, $post, $url_post)
    {
        if (config('publication.merge_comment_post') == false) {
            return true;
        }

        // The staff can write a comment under their post
        // Персонал может писать комментарий под своим постом
        if (UserData::checkAdmin()) {
            return true;
        }

        // If there are no replies to the post and the author of the post = the author of the comment, then add the comment to the end of the post
        // Если ответов на пост нет и автор поста = автора ответа, то дописываем ответ в конец поста
        if ((CommentModel::getNumberComment($post['post_id']) == null) && ($post['post_user_id'] == $this->user['id'])) {

            CommentModel::mergePost($post['post_id'], $content);

            redirect($url_post);
        }

        return true;
    }
	
	public function joinComment($content, $url_post, $parent)
	{
        if (config('publication.merge_comments') == false) {
            return true;
        }

        // The staff can write a repeated comment
        // Персонал может писать повторный комментарий
        if (UserData::checkAdmin()) {
            return true;
        }

		// If the participant has already responded to a specific comment and comments again
		// Если участник уже дал ответ на конкретный комментарий и комментирует повторно
		if ($comment = CommentModel::isResponseUser($parent['comment_id'])) {
			
			CommentModel::mergeComment($content, $comment['comment_id']);
			
			redirect($url_post  . '#comment_' . $comment['comment_id']);
		}
		
		return true;
	}

    // Notifications when adding a answer
    // Уведомления при добавлении ответа
    public function notifPost($content, $post, $comment_id, $url)
    {
        // Contact via @
        // Обращение через @
        if ($message = \App\Services\Parser\Content::parseUsers($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_ANSWER, $message, $url, $post['post_user_id']);
        }

        // Who is following this question/post
        // Кто подписан на данный вопрос / пост
        if ($focus_all = PostModel::getFocusUsersPost($post['post_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $this->user['id']) {
                    NotificationModel::send($focus_user['signed_user_id'], NotificationModel::TYPE_AMSWER_POST, $url);
                }
            }
        }
		
		// Notifications when adding a comment
		// Уведомления при добавлении комментария
		if ($comment_id) {
            $comment = CommentModel::getCommentId($comment_id);
            if ($this->user['id'] != $comment['comment_user_id']) {
              NotificationModel::send($comment['comment_user_id'], NotificationModel::TYPE_COMMENT_COMMENT, $url);
            }
        }
    }
}
