<?php
/*
 * Индекгация, авторизация через социальные службы (сайты)
 * Indexing, authorization through social services (websites)
 */

return [

    // SMTP
    // If smtp is enabled - 1, then fill in the settings at the bottom
    // Если smtp включен - 1, то заполните настройки вниэу
    'smtp'      => false,
    'smtp_user' => 'user@site.ru',
    'smtp_pass' => '******',
    'smtp_host' => 'smtp.yandex.ru',
    'smtp_port' => 465,

    // Капча. Если вкл. - 1, то прописываем ключи ниже
    // Captcha. If incl. - 1, then we register the keys below
    'captcha'               => false, 
    'captcha_public_key'    => '***',
    'captcha_private_key'   => '***',
    
    // Discord: 1 - включить
    // Discord WEBHOOK URL и имя бота: изменить на своё
    // icon_url - урл. аватарки сайта
    // Для настроек сервера Discord зайти в раздел Вебхуки, создать новый 
    'discord'               => false,
    'discord_webhook_url'   => 'https://discord.com/api/webhooks/****',
    'discord_name_bot'      => 'PostBot',
    'discord_icon_url'      => 'https://cdn.discordapp.com/avatars/****.png',
    
];