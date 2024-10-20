<?php

declare(strict_types=1);

namespace App\Content\Integration;

use Curl;

class Discord
{
    public static function addWebhook($text, $title, $url)
    {
        $text = strip_tags($text, '<p>');
        $text = preg_replace(['/(<p>)/', '(<\/p>)'], ['', '\n'], $text);

        // Проверяем имя бота и YOUR_WEBHOOK_URL
        if (!$webhookurl = config('integration', 'discord_webhook_url')) {
            return false;
        }
        if (!$usernamebot = config('integration', 'discord_name_bot')) {
            return false;
        }

        $content    = __('app.post');
        $color      = hexdec("3366ff");

        // Формируем даты
        $timestamp  = date("c");

        $json_data  = json_encode([

            // Сообщение над телом
            "content" => $content,

            // Ник бота который отправляет сообщение
            "username" => $usernamebot,

            // URL Аватара.
            // Можно использовать аватар загруженный при создании бота
            "avatar_url" => config('integration', 'discord_icon_url'),

            // Преобразование текста в речь
            "tts" => false,

            // Загрузка файла
            // "file" => "",

            // Массив Embeds
            "embeds" => [
                [
                    // Заголовок
                    "title" => $title,

                    // Тип Embed Type, не меняем
                    "type" => "rich",

                    // Описание
                    "description" => $text,

                    // Ссылка в заголовке url
                    "url" => config('meta', 'url') . $url,

                    // Таймштамп, обязательно в формате ISO8601
                    "timestamp" => $timestamp,

                    // Цвет границы слева, в HEX
                    "color" => $color,

                    // Подпись и аватар в подвале sitename
                    "footer" => [
                        "text" => config('integration', 'discord_name_bot'),
                        "icon_url" => config('integration', 'discord_icon_url'),
                    ],
                ]
            ]

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        Curl::index($webhookurl, $json_data, ['Content-type: application/json']);

        return true;
    }
}
