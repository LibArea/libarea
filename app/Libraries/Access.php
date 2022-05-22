<?php

use Hleb\Constructor\Handlers\Request;
use App\Models\ActionModel;

class Access
{
    public static function limitForMiddleware()
    {
        $type = Request::get('type');

        if (UserData::checkAdmin()) {
            return true;
        }

        self::limitingMode();

        // TODO: Изменим поля в DB, чтобы использовать limitContent для messages и invites: 
        if (in_array($type, ['post', 'amswer', 'comment', 'item', 'team'])) {
            if (self::limitContent($type) === false) {
                Html::addMsg(__('msg.limit_day', ['tl' => UserData::getUserTl()]), 'error');
                redirect('/');
            }
        }
    }

    /**
     * Stop changing (adding) content if the user is frozen (silent mode)
     *
     * Остановим изменение (добавление) контента если пользователь заморожен (немой режим)
     */
    public static function limitingMode()
    {
        if (UserData::getLimitingMode() == 1) {
            Html::addMsg(__('msg.silent_mode',), 'error');
            redirect('/');
        }
        return true;
    }

    /**
     * From what TL level is it possible to create content.
     *
     * In config: tl_add_post
     */
    public static function limitContent($type)
    { 

        /**
         * From what TL level is it possible to create content.
         *
         * С какого уровня TL возможно создавать контент.
         *
         * In config: tl_add_post
         */
        if (self::trustLevels(config('trust-levels.tl_add_' . $type)) == false) {
            return false;
        }

        /**
         * Limit per day for the level of confidence, taking into account coefficients.
         *
         * Лимит за сутки для уровня доверия с учетом коэффициентов.
         *
         * In config: perDay_post
         */
        $сount = ActionModel::getSpeedTime(UserData::getUserId(), $type);

        $total = config('trust-levels.perDay_' . $type) * config('trust-levels.multiplier_' . UserData::getUserTl());

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
    public static function trustLevels($trust_level)
    {
        if (UserData::getUserTl() < $trust_level) {
            return false;
        }

        return true;
    }

    /**
     * Content type, content author, time added and how much time can be edited.
     *
     * Тип контента, автор контента, время добавления и сколько времени можно редактировать.
     */
    public static function author($type_content, $author_id, $adding_time, $limit_time = false)
    {
        if (UserData::checkAdmin()) {
            return true;
        }

        // Доступ получает только автор
        if ($author_id != UserData::getUserId()) {
            return false;
        }

        self::limiTime($adding_time, $limit_time);

        return true;
    }

    /**
     * Time limits after posting.
     *
     * Лимиты на время после публикации.
     */
    public static function limiTime($adding_time, $limit_time = false)
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
}
