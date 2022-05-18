<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\{ActionModel, AuditModel, NotificationModel, PostModel};
use UserData, Html, Validation;

class AuditController extends Controller
{
    // Let's check the stop words, url
    // Проверим стоп слова, url
    public function prohibitedContent($content, $type)
    {

        if (!self::stopUrl($content, $this->user['id'])) {
            return false;
        }

        if (!self::stopWords($content, $this->user['id'])) {
            return false;
        }

        return true;
    }

    // If there is a link and the total contribution (adding posts, replies and comments) is less than N 
    // Если есть ссылка и общий вклад (добавления постов, ответов и комментариев) меньше N
    public static function stopUrl($content, $uid)
    {
        if (self::estimationUrl($content)) {
            $all_count = ActionModel::allContentUserCount($uid);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($uid);
                Html::addMsg(__('msg.content_audit'), 'error');
                return false;
            }
        }
        return true;
    }

    // If the word is on the stop list and the total contribution is minimal (less than 2)
    // Если слово в стоп листе и общий вклад минимальный (меньше 2)
    public static function stopWords($content, $uid)
    {
        if (self::stopWordsExists($content)) {
            $all_count = ActionModel::allContentUserCount($uid);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($uid);
                Html::addMsg(__('msg.content_audit'), 'error');
                return false;
            }
        }
        return true;
    }
    
    // For URL trigger 
    // Для триггера URL
    public static function estimationUrl($content)
    {
        $regex = '/(?<!!!\[\]\(|"|\'|\=|\)|>)(https?:\/\/[-a-zA-Z0-9@:;%_\+.~#?\&\/\/=!]+)(?!"|\'|\)|>)/i';
        if (preg_match($regex, $content, $matches)) {
            return  $matches[1];
        }
        return false;
    }

    /// Check the presence of the word in the stop list (audit in the admin panel) 
    // Проверим наличия слова в стоп листе (аудит в админ-панели)
    public static function stopWordsExists($content)
    {
        $stop_words = AuditModel::getStopWords();

        foreach ($stop_words as $word) {

            $word = trim($word['stop_word']);

            if (!$word) {
                continue;
            }

            if (substr($word, 0, 1) == '{' and substr($word, -1, 1) == '}') {

                if (preg_match(substr($word, 1, -1), $content)) {
                    return true;
                }
            } else {
                if (strstr($content, $word)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function create($type, $last_content_id, $url)
    {
        AuditModel::add(
            [
                'action_type'       => $type,
                'type_belonging'    => 'audit',
                'user_id'           => $this->user['id'],
                'content_id'        => $last_content_id,
            ]
        );

        // Send notification type 21 (audit) to administrator (id 1) 
        // Отправим тип уведомления 21 (аудит) администратору (id 1)
        NotificationModel::send(
            [
                'sender_id'    => $this->user['id'],
                'recipient_id' => UserData::REGISTERED_ADMIN_ID,
                'action_type'  => NotificationModel::TYPE_AUDIT,
                'url'          => $url,
            ]
        );

        return true;
    }

    public function report()
    {
        $content_type   = Request::getPost('type');
        $post_id        = Request::getPostInt('post_id');
        $content_id     = Request::getPostInt('content_id');

        // Limit the flags
        if ($this->user['trust_level'] == config('trust-levels.tl_stop_report')) return 1;

        $num_report =  AuditModel::getSpeedReport($this->user['id']);
        if ($num_report > config('trust-levels.all_stop_report')) return 1;

        $post   = PostModel::getPost($post_id, 'id', $this->user);
        Html::pageError404($post);

        $arr = ['post', 'answer', 'comment'];
        if (!in_array($content_type, $arr)) {
            return false;
        }

        $type_id = $content_type == 'answer' ? 'answer_' . $content_id : 'comment_' . $content_id;

        $slug   = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $url    = $slug . '#' . $type_id;

        // Admin notification 
        // Оповещение админу
        NotificationModel::send(
            [
                'sender_id'    => $this->user['id'],
                'recipient_id' => UserData::REGISTERED_ADMIN_ID,
                'action_type'  => NotificationModel::TYPE_REPORT,
                'url'          => $url,
            ]
        );

        AuditModel::add(
            [
                'action_type'       => $content_type,
                'type_belonging'    => 'report',
                'user_id'           => $this->user['id'],
                'content_id'        => $content_id,
            ]
        );
    }
}
