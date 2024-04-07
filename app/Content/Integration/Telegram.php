<?php

declare(strict_types=1);

namespace App\Content\Integration;

class Telegram
{
    // PHP Telegram Bot based on the official Telegram Bot API
    // We are watching: https://github.com/php-telegram-bot/core
    // Stub for Telegram
    public static function addWebhook($text, $title, $url)
    {
        // $url        = 'https://api.telegram.org';
        $token      = config('integration', 'telegram_token');
        $chat_id    = config('integration', 'chat_id');
        $txt        = rawurlencode($title);

        // file_get_contents($url . '/bot'. $token .'/sendMessage?chat_id='. $chat_id .'&disable_notification=true&parse_mode=HTML&text=' . $txt);
        fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}", "r");
    }
}
