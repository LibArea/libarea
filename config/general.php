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

    // Шаблон по умолчанию  + какие языки есть в системе
    // Default template + what languages are in the system
    'template'  => 'default',
    'templates' => [
        'default'   => 'Default',
        'qa'        => 'Q&A',
        'minimum'   => 'Minimum',
    ],

    // Default localization (+ languages represented)
    // Локализация по умолчанию (+ какие языки есть в системе)
    'lang'  => 'ru',
    'languages'     => [
        'ru'        => 'Русский',
        'ua'        => 'Український',
        'en'        => 'English',
        'tr'        => 'Turkish',
        'zh_CN'     => '简体中文',
        'ro'        => 'România',
        'ar'        => 'العربية',
        'vi'        => 'Tiếng Việt',
    ],

    // To force an update (css, js)
    'version' 	=> 121,

    // Set to True to format Q&A posts (discussion option will be hidden)
    // Установить на True чтобы сделать формат постов Q&A (дискуссионный вариант будет скрыт)
    'qa_site_format'    => false,

    // Real time notifications. Update: 15 seconds
    // Уведомления в реальном времени. Обновление: 15 секунд
    'notif_update_time' =>  15000,

    // If TRUE, then the first 50 participants will have TL2 upon registration (otherwise TL1)
    // Если TRUE, то при регистрации первые 50 участников будет иметь TL2 (в противном случае TL1)
    'mode'              => true,

    // Can a user delete a profile?
    // Пользователь может удалять профиль?
    'deleting_profile'  =>  false,

    // Email of the site administration
    // Email администрации сайта
    'email'             => 'libarea@yandex.ru',

    // Confirm sender (email must be configured on the server).
    // Подтвердить отправителя (email должен быть настроен на сервере).
    'confirm_sender'    =>  false,

    // Check email (during registration)? If false, then the SMTP (and PHP Mail) settings in config/integration.php will not work.
    // Проверять почту (при регистрации)? Если false, то настройки SMTP (и PHP Mail) в config/integration.php работать не будут.
    'mail_check'        =>  true,
];
