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
    
    // Default template (default).
    // Шаблон по умолчанию (default).
    'template'          => 'default',
    
    // Which templates are available for users to choose from
    // Какие шаблоны доступны для выбора участникам
    'templates'         => ['default', 'qa', 'minimum'],

    // Default localization (+ languages represented)
    // Локализация по умолчанию (+ какие языки есть в системе)
    'lang'              => 'ru',
    'languages'         => ['ru', 'ua', 'de', 'en', 'fr', 'ro', 'ar', 'zh_CN', 'zh_TW'],

    // Set to True to format Q&A posts (discussion option will be hidden)
    // Установить на True чтобы сделать формат постов Q&A (дискуссионный вариант будет скрыт)
    'qa_site_format'    => false,

    // Real time notifications. Update: 15 seconds
    // Уведомления в реальном времени. Обновление: 15 секунд
    'notif_update_time' =>  15000,

    // If TRUE, then the first 50 participants will have TL2 upon registration (otherwise TL1)
    // Если TRUE, то при регистрации первые 50 участников будет иметь TL2 (в противном случае TL1)
    'mode'              => true, 
        
    // Email of the site administration
    // Email администрации сайта
    'email'             => '***@site.ru',

    // Confirm sender (email must be configured on the server).
    // Подтвердить отправителя (email должен быть настроен на сервере).
    'confirm_sender'    =>  false,

    // Check email (during registration)? If false, then the SMTP (and PHP Mail) settings in config/integration.php will not work.
    // Проверять почту (при регистрации)? Если false, то настройки SMTP (и PHP Mail) в config/integration.php работать не будут.
    'mail_check'        =>  true,
];
