<?php

use App\Models\ContentModel;

class Validation
{
    public static function checkEmail($email, $redirect)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            addMsg(Translate::get('Invalid email address'), 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function Limits($name, $content, $min, $max, $redirect)
    {
        if (self::getStrlen($name) < $min || self::getStrlen($name) > $max) {

            $text = sprintf(Translate::get('text-string-length'), '«' . $content . '»', $min, $max);
            addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function charset_slug($slug, $text, $redirect)
    {
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $slug)) {

            $text = sprintf(Translate::get('text-charset-slug'), '«' . $text . '»');
            addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    // Длина строки
    public static function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
    }

    // Права для TL
    // $trust_leve - уровень доверие участника
    // $allowed_tl - с какого TL разрешено
    // $count_content - сколько уже создал
    // $count_total - сколько разрешено
    public static function validTl($trust_level, $allowed_tl, $count_content, $count_total)
    {

        if ($trust_level < $allowed_tl) {
            return false;
        }

        if ($count_content >= $count_total) {
            return false;
        }

        return true;
    }

    // Отправки личных сообщений (ЛС)
    // $uid - кто отправляет
    // $user_id - кому
    // $add_tl -  с какого уровня доверия
    public static function accessPm($uid, $user_id, $add_tl)
    {
        // Запретим отправку себе
        if ($uid['user_id'] == $user_id) {
            return false;
        }

        // Если уровень доверия меньше установленного
        if ($add_tl > $uid['user_trust_level']) {
            return false;
        }

        return true;
    }

    // Частота добавления контента в день
    public static function speedAdd($uid, $type)
    {
        $number =  ContentModel::getSpeed($uid['user_id'], $type);
        if ($uid['user_trust_level'] >= 0 && $uid['user_trust_level'] <= 2) {

            if ($number >= Config::get('trust-levels.tl_' . $uid['user_trust_level'] . '_add_' . $type)) {
                self::inform($uid['user_trust_level'], $type . 's');
            }
        }

        if ($number > Config::get('trust-levels.all_limit')) {
            self::inform($uid['user_trust_level'], 'messages');
        }

        return true;
    }

    public static function inform($tl, $content)
    {
        $text = sprintf(Translate::get('limit-content-day'), 'TL' . $tl, '«' . Translate::get($content) . '»');
        addMsg($text, 'error');
        redirect('/');
    }
}
