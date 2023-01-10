<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Services\Сheck\PostPresence;
use App\Models\{ActionModel, AuditModel, NotificationModel};
use UserData, Msg;

class Audit extends Base
{
    protected $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index()
    {
        $content_type   = Request::getPost('type');
        $post_id        = Request::getPostInt('post_id');
        $content_id     = Request::getPostInt('content_id');

        // Limit the flags
        if ($this->user['trust_level'] < config('trust-levels.tl_add_report')) return 1;

        if (AuditModel::getSpeedReport($this->user['id']) > config('trust-levels.perDay_report')) return 1;

        $post = PostPresence::index($post_id, 'id');

        if (!in_array($content_type, ['post', 'answer', 'comment'])) return false;

        $type_id    = $content_type == 'answer' ? 'answer_' . $content_id : 'comment_' . $content_id;
        $url        = post_slug($post['post_id'], $post['post_slug']) . '#' . $type_id;

        $this->create($content_type, $content_id, $url, 'report');

        return true;
    }

    // Let's check the stop words, url
    // Проверим стоп слова, url
    public function prohibitedContent(string $content)
    {

        if (!self::stopUrl($content, (int)$this->user['id'])) {
            return false;
        }

        if (!self::stopWords($content, (int)$this->user['id'])) {
            return false;
        }

        return true;
    }

    // If there is a link and the total contribution (adding posts, replies and comments) is less than N 
    // Если есть ссылка и общий вклад (добавления постов, ответов и комментариев) меньше N
    public static function stopUrl(string $content, int $user_id)
    {
        if (self::estimationUrl($content)) {
            $all_count = ActionModel::allContentUserCount($user_id);
            if ($all_count < config('trust-levels.total_contribution')) {
                ActionModel::addLimitingMode($user_id);
                Msg::add(__('msg.content_audit'), 'error');
                return false;
            }
        }
        return true;
    }

    // If the word is on the stop list and the total contribution is minimal (less than 2)
    // Если слово в стоп листе и общий вклад минимальный (меньше 2)
    public static function stopWords(string $content, int $user_id)
    {
        if (self::stopWordsExists($content)) {
            $all_count = ActionModel::allContentUserCount($user_id);
            if ($all_count < config('trust-levels.total_contribution')) {
                ActionModel::addLimitingMode($user_id);
                Msg::add(__('msg.content_audit'), 'error');
                return false;
            }
        }
        return true;
    }

    // For URL trigger 
    // Для триггера URL
    public static function estimationUrl(string $content)
    {
        $regex = '/(?<!!!\[\]\(|"|\'|\=|\)|>)(https?:\/\/[-a-zA-Z0-9@:;%_\+.~#?\&\/\/=!]+)(?!"|\'|\)|>)/i';
        if (preg_match($regex, $content, $matches)) {
            return  $matches[1];
        }
        return false;
    }

    /// Check the presence of the word in the stop list (audit in the admin panel) 
    // Проверим наличия слова в стоп листе (аудит в админ-панели)
    public static function stopWordsExists(string $content)
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

    public function create(string $type, int $last_content_id, string $url, string $type_notification = 'audit')
    {
        $action_type = ($type_notification == 'audit') ? NotificationModel::TYPE_AUDIT : NotificationModel::TYPE_REPORT;

        AuditModel::add(
            [
                'action_type'       => $type,
                'type_belonging'    => $type_notification,
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
                'action_type'  => $action_type,
                'url'          => $url,
            ]
        );

        return true;
    }
}
