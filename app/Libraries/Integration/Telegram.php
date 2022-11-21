<?php

class Telegram
{
    // Stub for Telegram
    public static function addWebhook($text, $title, $url)
    {
        $webhookurl = 'https://api.telegram.org/bot' . config('integration.telegram_token') . '/sendMessage';

        $params  = [
            'chat_id'       => config('integration.chat_id'),
            'parse_mode'    => 'HTML',
            'text'          => $text,
        ];

        Curl::index($webhookurl, $params, ['Content-type: application/json']); 
    }
}
