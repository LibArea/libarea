<?php

declare(strict_types=1);

namespace App\Controllers\Comment;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\{PostPresence, CommentPresence};
use App\Models\{NotificationModel, ActionModel, CommentModel, PostModel};
use App\Validate\Validator;
use DetectMobile;

class AddCommentController extends Controller
{
    /**
     * Let's show the form for adding a comment
     * Покажем форму добавление комментария
     *
     * @return void
     */
    public function index()
    {
        insert(
            '/_block/form/form-for-add',
            [
                'data'  => [
                    'id'    => Request::post('id')->asInt(),
                    'type'     => 'comment',
                ]
            ]
        );
    }

    public function add()
    {
        if ($post_id = Request::post('post_id')->asInt()) {
            $post = PostPresence::index($post_id);
        }

        // Will get the id of the comment that is being answered (if there is one, i.e. not the root comment)
        // Получит id комментария на который идет ответ (если он есть, т.е. не корневой комментарий)
        if ($parent_id = Request::post('id')->asInt()) {

            // Let's check if there is a comment
            // Проверим наличие комментария
            $comment = CommentPresence::index($parent_id);

            notEmptyOrView404($comment);

            // Let's check that this comment belongs to a post that is
            // Проверим, что данный комментарий принадлежит посту который есть
            $post = PostPresence::index($comment['comment_post_id'], 'id');
        }

        notEmptyOrView404($post);

        $url_post = post_slug($post['post_id'], $post['post_slug']);

        Validator::Length($content = $_POST['content'], 6, 5000, 'content', $url_post);

        // Let's check the stop words, url
        // Проверим стоп слова и url
        $trigger = (new \App\Controllers\AuditController())->prohibitedContent($content);

        // Different conditions for combining a comment (with a post or your previous one)
        // Различные условия объединения комментария (с постом или своим предыдущим)  
        $this->joinPost($content, $post, $url_post);
        $this->joinComment($content, $url_post, $comment ?? false);

        $last_id = CommentModel::add($post['post_id'], $parent_id, $content, $trigger, DetectMobile::index());

        // Add an audit entry and an alert to the admin
        // Аудит и оповещение персоналу
        if ($trigger === false) {

            $url = $url_post . '#' . 'comment_' . $last_id;

            (new \App\Controllers\AuditController())->create('comment', (int)$last_id, $url);
        }

        $url = $url_post . '#comment_' . $last_id;

        $this->notifPost($content, $post, $parent_id, $url);

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
        if (config('publication', 'merge_comment_post') == false) {
            return true;
        }

        // The staff can write a comment under their post
        // Персонал может писать комментарий под своим постом
        if ($this->container->user()->admin()) {
            return true;
        }

        // If there are no replies to the post and the author of the post = the author of the comment, then add the comment to the end of the post
        // Если ответов на пост нет и автор поста = автора ответа, то дописываем ответ в конец поста
        if ((CommentModel::getNumberComment($post['post_id']) == null) && ($post['post_user_id'] == $this->container->user()->id())) {

            CommentModel::mergePost($content, $post['post_id']);

            redirect($url_post);
        }

        return true;
    }

    public function joinComment($content, $url_post, $parent): bool
    {
        if (!config('publication', 'merge_comments')) {
            return false;
        }

        if (!$parent) {
            return false;
        }

        // The staff can write a repeated comment
        // Персонал может писать повторный комментарий
        if ($this->container->user()->admin()) {
            return false;
        }

        // If the participant has already responded to a specific comment and comments again
        // Если участник уже дал ответ на конкретный комментарий и комментирует повторно
        if ($comment = CommentModel::isResponseUser($parent['comment_id'])) {

            CommentModel::mergeComment($content, $comment['comment_id']);

            redirect($url_post  . '#comment_' . $comment['comment_id']);
        }

        return true;
    }

    /**
     * Notifications when adding a answer
     * Уведомления при добавлении ответа
     *
     * @param string $content
     * @param array $post
     * @param integer $parent_id
     * @param string $url
     * @return void
     */
    public function notifPost(string $content, array $post, int $parent_id, string $url)
    {
        // Contact via @
        // Обращение через @
        if ($message = \App\Content\Parser\Content::parseUsers($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_ANSWER, $message, $url, $post['post_user_id']);
        }

        // Notifications when adding a comment
        // Уведомления при добавлении комментария
        if ($parent_id) {
            $comment = CommentModel::getCommentId($parent_id);
            if ($this->container->user()->id() != $comment['comment_user_id']) {
                NotificationModel::send($comment['comment_user_id'], NotificationModel::TYPE_COMMENT_COMMENT, $url);
            }
        } else {

            // Who is following this question/post
            // Кто подписан на данный вопрос / пост
            if ($focus_all = PostModel::getFocusUsersPost($post['post_id'])) {
                foreach ($focus_all as $focus_user) {
                    if ($focus_user['signed_user_id'] != $this->container->user()->id()) {
                        NotificationModel::send($focus_user['signed_user_id'], NotificationModel::TYPE_AMSWER_POST, $url);
                    }
                }
            }
        }
    }
}
