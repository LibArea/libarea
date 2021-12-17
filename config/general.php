<?php
/*
 * Главные, функциональные настройки сайта
 * Main, functional site settings 
 */

return [
    // Инвайты: 1 - только по приглашению
    // Invites: 1 - by invitation only
    'invite'            => 0,

    // Служба внешнего поиска: 1 - включена. См. документацию.
    // External search service: 1 - enabled. See the documentation.
    'search'            => 0,

    // Шаблон по умолчанию (default).
    // Default template (default).
    'template'          => 'default',

    // Какие шаблоны доступны для выбора участникам
    // Which templates are available for users to choose from
    'templates'         => ['default'],

    // Локализация по умолчанию (+ какие языки есть в системе)
    // Default localization (+ languages represented)
    'lang'              => 'ru',
    'languages'         => ['ru', 'en', 'md', 'fr', 'zh', 'de'],

    // Режим запуска: 1 - включить
    // Для первых 50 участников TL будет 1
    // Если устновить на 0, то при регистрации будет TL0
    // Launch mode: 1 - enable
    // If you set it to 0, then when registering it will be TL0
    'mode'              => 1,

    // Капча. Если вкл. - 1, то прописываем ключи ниже
    // Captcha. If incl. - 1, then we register the keys below
    'captcha'           => 0,
    'public_key'        => '***',
    'private_key'       => '***',

    // Discord: 1 - включить
    // Discord WEBHOOK URL и имя бота: изменить на своё
    // icon_url - урл. аватарки сайта
    // Для настроек сервера Discord зайти в раздел Вебхуки, создать новый 
    'discord'           => 0,
    'webhook_url'       => 'https://discord.com/api/webhooks/***',
    'name_bot'          => 'PostBot',
    'icon_url'          => 'https://cdn.discordapp.com/avatars/***.png',

    // Email администрации сайта
    // Email of the site administration
    'email'             => '***@***.com',

    // SMTP
    // If smtp is enabled - 1, then fill in the settings at the bottom
    // Если smtp включен - 1, то заполните настройки вниэу
    'smtp'              => 0,
    'smtpuser'          => '***@***.com',
    'smtppass'          => '******',
    'smtphost'          => 'smtp.yandex.ru',
    'smtpport'          => 465,
];
