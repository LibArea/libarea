<?php

if (file_exists(__DIR__ . '/main-local.php')) {
    return (require __DIR__ . '/main-local.php');
}

/*
 * This file contains user settings that can be overridden in modules when creating
 * a similar /modules/{module_name}/config/main.php file.
 *
 * В этом файле находятся пользовательские настройки, которые можно переопределить в модулях
 * при создании аналогичного файла /modules/{module_name}/config/main.php.
 */

return [
    /*
    │-----------------------------------------------------------------------------
    │ Default session initialization
    │-----------------------------------------------------------------------------
    │
    │ If the project does not use sessions, then in this case you can not activate
    │ them when loading the framework.
    │ In this case, you can selectively (manually) use their initialization.
    │ The plain(...) method in a route overrides this parameter.
    │ Default: true
    │
    │
    │-----------------------------------------------------------------------------
    │ Инициализация сессий по умолчанию
    │-----------------------------------------------------------------------------
    │
    │ Если в проекте не используются сессии, то в этом случае можно их не
    │ активировать при загрузке фреймворка.
    │ При этом можно выборочно (вручную) использовать их инициализацию.
    │ Метод plain(...) в маршруте переопределяет этот параметр.
    │ Изначально: true
    │
    */
    'session.enabled' => true,

    /*
    │-----------------------------------------------------------------------------
    │ Logging database queries
    │-----------------------------------------------------------------------------
    │
    │ Database queries are saved according to the current logging method.
    │ Default: false
    │
    │
    │-----------------------------------------------------------------------------
    │ Выводить в логи запросы к базе данных
    │-----------------------------------------------------------------------------
    │
    │ Запросы к базам данных сохраняются согласно текущему способу логирования.
    │ Изначально: false
    │
    */
    'db.log.enabled' => false,

    /*
    │-----------------------------------------------------------------------------
    │ User session cookie parameters
    │-----------------------------------------------------------------------------
    │
    │ Setting parameters for session Cookie.
    │ The values are similar to `options` in the PHP function setcookie().
    │ ('expires', 'path', 'domain', 'secure', 'httponly', 'samesite')
    │ The 'expires' parameter takes precedence over the max.session.lifetime value.
    │ Default: [] (standard framework settings)
    │
    │
    │-----------------------------------------------------------------------------
    │ Параметры пользовательской сессионной Cookie
    │-----------------------------------------------------------------------------
    │
    │ Установка параметров для сессионной Cookie.
    │ Значения аналогично `options` в PHP функции setcookie().
    │ ('expires', 'path', 'domain', 'secure', 'httponly', 'samesite')
    │ Параметр 'expires' имеет приоритет выше, чем значение max.session.lifetime.
    │ Изначально: [] (стандартные настройки фреймворка)
    │
    */
    'session.options' => [],

    /*
    │-----------------------------------------------------------------------------
    │ Not used
    │-----------------------------------------------------------------------------
    │
    │ Current value in config/general.php file
    │ Initially: '*'
    │
    │
    │-----------------------------------------------------------------------------
    │ Не используется
    │-----------------------------------------------------------------------------
    │
    │ Актуальное значение в файле config/general.php
    │ Изначально: '*'
    │
    */
    'default.lang' => '*',
    'allowed.languages' => ['*'],

    /*
     * If you need to add your own settings, you can assign them here.
     * In the future, they will be available from the setting(...) function or
     * Hleb\Static\Settings::getParam('main', ...) by setting name.
     *
     * Если необходимо добавить собственные настройки, то их можно назначить здесь.
     * В дальнейшем они будут доступны из функции setting(...) или
     * Hleb\Static\Settings::getParam('main', ...) по названию настройки.
     */

    //...//

    // Атрибут nonce используется для установки цифровой подписи на конкретный скрипт.
    'nonce' => bin2hex(random_bytes((int)'12')),

    // Список разрешённых ресурсов для Content-Security-Policy.
    'allowed.resources' => [
        'default-src' => [
            'https://www.google.com',
            'https://www.youtube.com',
            'https://rutube.ru',
            'https://vk.com',
            'https://rutube.ru/api',
            'https://mc.yandex.ru',
        ],
        'style-src' => [
            'http://new.loc'
        ],
        'script-src' => [
            'https://www.google.com',
            'https://www.gstatic.com',
            'https://mc.yandex.ru',
            'https://yastatic.net',
        ],
        'img-src' => [
            'https://*.userapi.com/impg/',
        ],
        // For some versions of Elge browser
        'font-src' => [
            'https://libarea.ru',
        ]
    ],
];
