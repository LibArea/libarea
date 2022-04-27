<?php
/*
 * Главные, функциональные настройки сайта
 * Main, functional site settings 
 */

return [

    // TRUE - сайт выключен
    // TRUE - сайт выключен
    'site_disabled'     => false,

    // TRUE - только по приглашению
    // TRUE - by invitation only
    'invite'            => false,
    'invite_limit'      => 5,
    
    // Шаблон по умолчанию (default).
    // Default template (default).
    'template'          => 'default',
    
    // Какие шаблоны доступны для выбора участникам
    // Which templates are available for users to choose from
    'templates'         => ['default', 'qa', 'test'],

    // Локализация по умолчанию (+ какие языки есть в системе)
    // Default localization (+ languages represented)
    'lang'              => 'ru',
    'languages'         => ['ru', 'en', 'ro', 'fr', 'zh', 'de'],

    // Режим запуска: true - включить
    // Для первых 50 участников TL будет 1
    // Если устновить на false, то при регистрации будет TL0
    // Launch mode: true - enable
    // If you set it to false, then when registering it will be TL0
    'mode'              => true, 
        
    // Капча. Если вкл. - true, то прописываем ключи ниже
    // Captcha. If incl. - true, then we register the keys below
    'captcha'           => false, 
    'public_key'        => '***',
    'private_key'       => '***',
    
    // Discord: true - включить
    // Discord WEBHOOK URL и имя бота: изменить на своё
    // icon_url - урл. аватарки сайта
    // Для настроек сервера Discord зайти в раздел Вебхуки, создать новый 
    'discord'           => false,
    'webhook_url'       => 'https://discord.com/api/webhooks/***',
    'name_bot'          => 'PostBot',
    'icon_url'          => 'https://cdn.discordapp.com/avatars/***.png',
    
    // Email администрации сайта
    // Email of the site administration
    'email'             => '***',
     
    // SMTP
    // If smtp is enabled - true, then fill in the settings at the bottom
    // Если smtp включен - true, то заполните настройки вниэу
    'smtp'              => false,
    'smtpuser'          => '***',
    'smtppass'          => '***',
    'smtphost'          => '***',
    'smtpport'          => 465,
];