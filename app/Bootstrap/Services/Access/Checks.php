<?php

declare(strict_types=1);

namespace App\Bootstrap\Services\Access;

use App\Bootstrap\Services\User\UserData;
use App\Bootstrap\Services\Auth\RegType;
use App\Models\ActionModel;
use Msg;

class Сhecks
{
    public static function postingFrequency(string $path)
    {
        $type = basename($path);

        // TODO: Изменим поля в DB, чтобы использовать limitContent для messages и invitation: 
        if (in_array($type, ['post', 'comment', 'item', 'poll'])) {
            if (self::limitContent($type) === false) {
                Msg::add(__('msg.limit_day'), 'error');
                redirect('/');
            }
        }
    }

    /**
     * From what TL level is it possible to create content.
     *
     * In config: tl_add_post
     */
    public static function limitContent(string $type): bool
    {
        /**
         * From what TL level is it possible to create content.
         *
         * С какого уровня TL возможно создавать контент.
         *
         * In config: tl_add_post
         */
        if (self::trustLevels(config('trust-levels', 'tl_add_' . $type)) == false) {
            return false;
        }

        /**
         * Limit per day for the level of confidence, taking into account coefficients.
         *
         * Лимит за сутки для уровня доверия с учетом коэффициентов.
         *
         * In config: perDay_post
         */
        $сount = ActionModel::getSpeedDay($type);

        $total = config('trust-levels', 'perDay_' . $type) * config('trust-levels', 'multiplier_' . UserData::getUserTl());

        if ($сount >= floor($total)) {
            return false;
        }

        return true;
    }

    /**
     * Trust Level Sharing.
     *
     * Общий доступ для уровня доверия.
     */
    public static function trustLevels(null|int $trust_level): bool
    {
        if (UserData::getUserTl() < $trust_level) {
            return false;
        }

        return true;
    }

    /**
     * Time limits after posting.
     *
     * Лимиты на время после публикации.
     */
    public static function limitTime(string $adding_time, int $limit_time = null): bool
    {
        if ($limit_time == true) {
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($adding_time);
            $time = floor($diff / 60);

            if ($time > $limit_time) {
                return false;
            }
        }

        return true;
    }

    /**
     * Content type, data array and how much time can be edited.
     *
     * Тип контента, массив данных и сколько времени можно редактировать.
     */
    public static function authorContent(string $type_content, array $info_type): bool
    {
        if (UserData::checkAdmin()) {
            return true;
        }

        /**
         * If the author's Tl has been downgraded.
         *
         * Если Tl автора было изменено на понижение.
         *
         * In config: tl_add_post
         */
        if (self::trustLevels(config('trust-levels', 'tl_add_' . $type_content)) === false) {
            return false;
        }

        /**
         * Only the author has access.
         *
         * Доступ получает только автор.
         */
        if ($info_type[$type_content . '_user_id'] != UserData::getUserId()) {
            return false;
        }

        /**
         * Time limit.
         *
         * Лимит по времени.
         */

        $time_edit = config('trust-levels', 'edit_time_' . $type_content);
        if ($type_content == 'post') {
            $time_edit = $info_type['post_draft'] == 1 ? 0 : config('trust-levels', 'edit_time_post');
        }

        if (self::limitTime($info_type[$type_content . '_date'], $time_edit) === false) {
            return false;
        }

        return true;
    }

    public static function postAuthorAndTeam(array $info_type, int $blog_user_id = 0): bool
    {
        if (UserData::checkAdmin()) {
            return true;
        }

        /**
         * If the author's Tl has been downgraded.
         *
         * Если Tl автора было изменено на понижение.
         *
         * In config: tl_add_post
         */
        if (self::trustLevels(config('trust-levels', 'tl_add_post')) === false) {
            return false;
        }

        /**
         * Allow the author or blog owner to edit the article.
         *
         * Разрешить редактировать статью автору или владельцу блога.
         */
        if ($info_type['post_user_id'] != UserData::getUserId() && UserData::getUserId() != $blog_user_id) {
            return false;
        }

        /**
         * Time limit.
         *
         * Лимит по времени.
         */
        $time_edit = $info_type['post_draft'] == 1 ? 0 : config('trust-levels', 'edit_time_post');
        if (self::limitTime($info_type['post_date'], $time_edit) === false) {
            return false;
        }

        return true;
    }

    /**
     * Content audit (posts, comments...). Visible only to the staff and the author.
     * Аудит контента (посты, комментарии...). Виден только персоналу и автору.
     * 
     * @param string $type_content
     * @param array $content
     * @return boolean
     */

    public static function auditСontent(string $type_content, array $content): bool
    {
        if (UserData::checkAdmin()) {
            return false;
        }

        if ($content[$type_content . '_user_id'] != UserData::getUserId() && $content[$type_content . '_published'] == 0) {
            return true;
        }

        return false;
    }

    /**
     * Hidden post. Visible only to the staff and the author.
     * Скрытые пост. Виден только персоналу и автору.
     *
     * @param array $post
     * @return boolean
     */
    public static function hiddenPost(array $post): bool
    {
        if (UserData::checkAdmin()) {
            return false;
        }

        if ($post['post_user_id'] != UserData::getUserId() && $post['post_hidden'] != 0) {
            return true;
        }

        return false;
    }

    public static function limitsLevel(int $type): bool
    {
        return RegType::check($type, '>=');
    }
}
