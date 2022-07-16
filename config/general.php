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
    'languages'         => ['ru', 'de', 'en', 'fr', 'ro', 'zh_CN', 'zh_TW'],

    // Режим запуска: true - включить
    // Для первых 50 участников TL будет 1
    // Если устновить на false, то при регистрации будет TL0
    // Launch mode: true - enable
    // If you set it to false, then when registering it will be TL0
    'mode'              => true, 
        
    // Email администрации сайта
    // Email of the site administration
    'email'             => '***@site.ru',

];