<?php
/*
 * Главные, функциональные настройки сайта
 * Main, functional site settings 
 */

return [

    // TRUE - site is disabled
    // TRUE - сайт выключен
    'site_disabled'     => false,

    // TRUE - by invitation only
    // TRUE - только по приглашению
    'invite'            => false,
    'invite_limit'      => 5,
    
    // Default template (default).
    // Шаблон по умолчанию (default).
    'template'          => 'default',
    
    // Which templates are available for users to choose from
    // Какие шаблоны доступны для выбора участникам
    'templates'         => ['default', 'qa', 'test'],

    // Default localization (+ languages represented)
    // Локализация по умолчанию (+ какие языки есть в системе)
    'lang'              => 'ru',
    'languages'         => ['ru', 'ua' 'de', 'en', 'fr', 'ro', 'zh_CN', 'zh_TW'],

    // If TRUE, then the first 50 participants will have TL2 upon registration (otherwise TL1)
    // Если TRUE, то при регистрации первые 50 участников будет иметь TL2 (в противном случае TL1)
    'mode'              => true, 
        
    // Email of the site administration
    // Email администрации сайта
    'email'             => '***@site.ru',

];