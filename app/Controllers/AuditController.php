<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{ContentModel, ActionModel, AuditModel, NotificationModel, PostModel};
use Config, UserData, Html;

class AuditController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Check the freeze and the amount of content per day 
    // Проверим заморозку и количество контента в день
    public function placementSpeed($content, $type)
    {
        self::stopContentQuietМode($this->user['limiting_mode']);

        $number =  ContentModel::getSpeed($this->user['id'], $type);

        self::stopLimit($this->user['trust_level'], $number, $type);

        if (!self::stopUrl($content, $this->user['id'])) {
            return false;
        }

        if (!self::stopWords($content, $this->user['id'])) {
            return false;
        }

        return true;
    }

    // Stop changing (adding) content if the user is "frozen"    
    // Остановим изменение (добавление) контента если пользователь "заморожен"
    public static function stopContentQuietМode($user_limiting_mode)
    {
        if ($user_limiting_mode == 1) {
            Html::addMsg('silent.mode', 'error');
            redirect('/');
        }
        return true;
    }

    // Checking limits on the level of trust of a participant 
    // Проверка лимитов по уровню доверия участника
    public static function stopLimit($user_trust_level, $number, $type)
    {
        if ($user_trust_level >= 0 && $user_trust_level <= 2) {
            if ($number >= Config::get('trust-levels.tl_' . $user_trust_level . '_add_' . $type)) {
                self::infoMsg($user_trust_level, $type . 's');
            }
        }

        if ($number > Config::get('trust-levels.all_limit')) {
            self::infoMsg($user_trust_level, 'messages');
        }
        return true;
    }

    // If there is a link and the total contribution (adding posts, replies and comments) is less than N 
    // Если есть ссылка и общий вклад (добавления постов, ответов и комментариев) меньше N
    public static function stopUrl($content, $uid)
    {
        if (self::estimationUrl($content)) {
            $all_count = AuditModel::ceneralContributionCount($uid);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($uid);
                Html::addMsg('content.audit', 'error');
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
            $all_count = AuditModel::ceneralContributionCount($uid);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($uid);
                Html::addMsg('content-audit', 'error');
                return false;
            }
        }
        return true;
    }

    public static function infoMsg($tl, $content)
    {
        Html::addMsg(__('limit.day', ['tl' => 'TL' . $tl, 'name' => __($content)]), 'error');

        redirect('/');
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
        $stop_words = ContentModel::getStopWords();

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
        if ($this->user['trust_level'] == Config::get('trust-levels.tl_stop_report')) return 1;

        $num_report =  AuditModel::getSpeedReport($this->user['id']);
        if ($num_report > Config::get('trust-levels.all_stop_report')) return 1;

        $post   = PostModel::getPost($post_id, 'id', $this->user);
        Html::pageError404($post);

        $arr = ['post', 'answer', 'comment'];
        if (!in_array($content_type, $arr)) {
            return false;
        }

        $type_id = $content_type == 'answer' ? 'answer_' . $content_id : 'comment_' . $content_id;

        $slug   = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
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
