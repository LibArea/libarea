<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationsModel, ActionModel, AnswerModel, CommentModel, PostModel, UserModel};
use Lori\{Content, Config, Base};

class AddCommentController extends \MainController
{

    // Добавление комментария
    public function index()
    {
        $comment_content    = Request::getPost('comment');
        $post_id            = Request::getPostInt('post_id');   // в каком посту ответ
        $answer_id          = Request::getPostInt('answer_id');   // на какой ответ
        $comment_id         = Request::getPostInt('comment_id');   // на какой комментарий

        $uid        = Base::getUid();
        $ip         = Request::getRemoteAddress();
        $post       = PostModel::getPostId($post_id);
        Base::PageError404($post);

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($uid['user_id'], 'id');
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        $redirect = '/post/' . $post['post_id'] . '/' . $post['post_slug'];

        // Проверяем длину тела
        Base::Limits($comment_content, lang('Comments-m'), '6', '2024', $redirect);

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении комментариев
        if ($uid['user_trust_level'] < Config::get(Config::PARAM_TL_ADD_COMM)) {
            $num_comm =  CommentModel::getCommentSpeed($uid['user_id']);
            if (count($num_comm) > 9) {
                Base::addMsg(lang('limit_comment_day'), 'error');
                redirect('/');
            }
        }

        $comment_published = 1;
        if (Content::stopWordsExists($comment_content)) {
            // Если меньше 2 комментариев и если контент попал в стоп лист, то заморозка
            $all_count = ActionModel::ceneralContributionCount($uid['user_id']);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($uid['user_id']);
                Base::addMsg(lang('limiting_mode_1'), 'error');
                redirect('/');
            }

            $comment_published = 0;
            Base::addMsg(lang('comment_audit'), 'error');
        }

        $comment_content = Content::change($comment_content);

        $data = [
            'comment_post_id'       => $post_id,
            'comment_answer_id'     => $answer_id,
            'comment_comment_id'    => $comment_id,
            'comment_content'       => $comment_content,
            'comment_published'     => $comment_published,
            'comment_ip'            => $ip,
            'comment_user_id'       => $uid['user_id'],
        ];

        $last_comment_id    = CommentModel::addComment($data);
        $url_comment        = $redirect . '#comment_' . $last_comment_id;

        if ($comment_published == 0) {
            ActionModel::addAudit('comment', $uid['user_id'], $last_comment_id);
            // Оповещение админу
            $type       = 15; // Упоминания в посте  
            $user_id    = 1;  // админу
            NotificationsModel::send($uid['user_id'], $user_id, $type, $last_comment_id, $url_comment, 1);
        }

        // Пересчитываем количество комментариев для поста + 1
        PostModel::updateCount($post_id, 'comments');

        // Оповещение автору ответа, что есть комментарий
        if ($answer_id) {
            // Себе не записываем (перенести в общий, т.к. ничего для себя не пишем в notf)
            $answ = AnswerModel::getAnswerId($answer_id);
            if ($uid['user_id'] != $answ['answer_user_id']) {
                $type = 4; // Ответ на пост        
                NotificationsModel::send($uid['user_id'], $answ['answer_user_id'], $type, $last_comment_id, $url_comment, 1);
            }
        }

        // Уведомление (@login)
        if ($message = Content::parseUser($comment_content, true, true)) {
            foreach ($message as $user_id) {
                // Запретим отправку себе и автору ответа (оповщение ему выше)
                if ($user_id == $uid['user_id'] || $user_id == $answ['answer_user_id']) {
                    continue;
                }
                $type = 12; // Упоминания в комментарии      
                NotificationsModel::send($uid['user_id'], $user_id, $type, $last_comment_id, $url_comment, 1);
            }
        }

        redirect($url_comment);
    }

    // Покажем форму
    public function add()
    {
        $uid  = Base::getUid();
        $data = [
            'answer_id'     => Request::getPostInt('answer_id'),
            'post_id'       => Request::getPostInt('post_id'),
            'comment_id'    => Request::getPostInt('comment_id'),
        ];

        return view(PR_VIEW_DIR . '/comment/add-form-answer-comment', ['data' => $data, 'uid' => $uid]);
    }
}
