<?php
/*
 * Индекгация, авторизация и др., через социальные службы (сайты)...
 * Indexing, authorization, etc., through social services (websites)...
 */

return [

    // If smtp is enabled - true, then fill in the settings at the bottom
    // Если smtp включен - true, то заполните настройки вниэу
    'smtp'      => false,
    'smtp_user' => 'user@site.ru',
    'smtp_pass' => '******',
    'smtp_host' => 'smtp.yandex.ru',
    'smtp_port' => 465,

    // Captcha. If incl. - true, then we register the keys below
    // Капча. Если вкл. - true, то прописываем ключи ниже
    'captcha'               => false, 
    'captcha_public_key'    => '******',
    'captcha_private_key'   => '******',
    
    // Discord WEBHOOK URL
    // For Discord server settings, go to the Webhooks section and create a new one 
    // Для настроек сервера Discord зайти в раздел Вебхуки и создайте новый 
    'discord'               => false,
    'discord_webhook_url'   => 'https://discord.com/api/webhooks/***',
    'discord_name_bot'      => 'PostBot',
    'discord_icon_url'      => 'https://cdn.discordapp.com/avatars/***.png',
    
    // Screenshots of the service https://screenshotone.com/
    // Скриншоты сервиса https://screenshotone.com/
    'sc_access_key' => '***',
    'sc_secret_key' => '***',
    
];
