<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\PostPresence;
use App\Models\{ActionModel, AuditModel, NotificationModel};
use Msg;

class AuditController extends Controller
{
    public const REGISTERED_ADMIN_ID = 1;

    public function index()
    {
        $content_type   = Request::post('type')->value();
        $post_id        = Request::post('post_id')->asInt();
        $content_id     = Request::post('content_id')->asInt();

        // Limit the flags
        if ($this->container->user()->tl() < config('trust-levels', 'tl_add_report')) return 1;

        if (AuditModel::getSpeedReport($this->container->user()->id()) > config('trust-levels', 'perDay_report')) return 1;

        $post = PostPresence::index($post_id, 'id');

        if (!in_array($content_type, ['post', 'comment'])) return false;

        $this->create($content_type, $content_id, '/post/' . $post['post_id'] . '/' . $post['post_slug'] . '#comment_' .  $content_id, 'report');

        return true;
    }

    /**
     * Let's check the stop words, url
     * Проверим стоп слова, url
     *
     * @param string $content
     */
    public function prohibitedContent(string $content): bool
    {
        if (!self::stopUrl($content, (int)$this->container->user()->id())) {
            return false;
        }

        if (!self::stopWords($content, (int)$this->container->user()->id())) {
            return false;
        }

        return true;
    }

    /**
     * If there is a link and the total contribution (adding posts, replies and comments) is less than N 
     * Если есть ссылка и общий вклад (добавления постов, ответов и комментариев) меньше N
     *
     * @param string $content
     * @param integer $user_id
     */
    public static function stopUrl(string $content, int $user_id): bool
    {
        if (self::estimationUrl($content)) {
            $all_count = ActionModel::allContentUserCount($user_id);
            if ($all_count < config('trust-levels', 'total_contribution')) {
                ActionModel::addLimitingMode($user_id);
                Msg::add(__('msg.content_audit'), 'error');
                return false;
            }
        }
        return true;
    }

    /**
     * If the word is on the stop list and the total contribution is minimal (less than 2)
     * Если слово в стоп листе и общий вклад минимальный (меньше 2)
     *
     * @param string $content
     * @param integer $user_id
     */
    public static function stopWords(string $content, int $user_id): bool
    {
        if (self::stopWordsExists($content)) {
            $all_count = ActionModel::allContentUserCount($user_id);
            if ($all_count < config('trust-levels', 'total_contribution')) {
                ActionModel::addLimitingMode($user_id);
                Msg::add(__('msg.content_audit'), 'error');
                return false;
            }
        }
        return true;
    }

    /**
     * For URL trigger 
     * Для триггера URL
     *
     * @param string $content
     */
    public static function estimationUrl(string $content)
    {
        $regex = '/(?<!!!\[\]\(|"|\'|\=|\)|>)(https?:\/\/[-a-zA-Z0-9@:;%_\+.~#?\&\/\/=!]+)(?!"|\'|\)|>)/i';
        if (preg_match($regex, $content, $matches)) {
            return  $matches[1];
        }
        return false;
    }

    /**
     * Check the presence of the word in the stop list (audit in the admin panel) 
     * Проверим наличия слова в стоп листе (аудит в админ-панели)
     *
     * @param string $content
     */
    public static function stopWordsExists(string $content)
    {
        $stop_words = AuditModel::getStopWords();

        foreach ($stop_words as $word) {

            $word = trim($word['stop_word']);

            if (!$word) {
                continue;
            }

            if ($word[0] === '{' && $word[strlen($word) - 1] === '}') {

                if (preg_match(substr($word, 1, -1), $content)) {
                    return true;
                }
            } else {
                if (str_contains($content, $word)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function create(string $type, int $last_content_id, string $url, string $type_notification = 'audit')
    {
        $action_type = ($type_notification === 'audit') ? NotificationModel::TYPE_AUDIT : NotificationModel::TYPE_REPORT;

        AuditModel::add(
            [
                'action_type'       => $type,
                'type_belonging'    => $type_notification,
                'user_id'           => $this->container->user()->id(),
                'content_id'        => $last_content_id,
            ]
        );

        // Send notification type 21 (audit) to administrator (id 1) 
        // Отправим тип уведомления 21 (аудит) администратору (id 1)
        NotificationModel::send(self::REGISTERED_ADMIN_ID, $action_type, $url);

        return true;
    }
}
