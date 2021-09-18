<?php
/*
 |-----------------------------------------------------------------------------
 | Project Debug Mode
 |-----------------------------------------------------------------------------
 |
 | Allows you to enable (true) / disable (false) debug mode.
 | “False” value is compulsory on a public server!
 | To hide the debug mode, add the '_debug=off' parameter to the url.
 | Default: true
 |
 |
 |-----------------------------------------------------------------------------
 | Режим отладки проекта
 |-----------------------------------------------------------------------------
 |
 | Позволяет включить (true) / выключить (false) режим отладки.
 | На публичном сервере обязательно значение false!
 | Чтобы скрыть режим отладки для отдельного url необходимо добавить к нему
 | GET-параметр '_debug=off'.
 | Изначально: true
 |
 */
define( 'HLEB_PROJECT_DEBUG', false );

/*
 |-----------------------------------------------------------------------------
 | Caching mode
 |-----------------------------------------------------------------------------
 |
 | Allows to enable (true) / disable (false) template caching.
 | Default: true
 |
 |
 |-----------------------------------------------------------------------------
 | Режим кэширования
 |-----------------------------------------------------------------------------
 |
 | Позволяет включить (true) / выключить (false) кэширование шаблонов.
 | Изначально: true
 |
 */
define( 'HLEB_TEMPLATE_CACHE', true );

/*
 |-----------------------------------------------------------------------------
 | Built-in automatic class loading
 |-----------------------------------------------------------------------------
 |
 | When you call classes, they are loaded. If ‘namespace’ of the class differs
 | from its real location, it is necessary to assign a match to such class in
 | the file '/app/Optional/MainConnector.php'. Disable if using another way.
 | Default: true
 |
 |
 |-----------------------------------------------------------------------------
 | Встроенная автоматическая загрузка классов
 |-----------------------------------------------------------------------------
 |
 | При вызове классов происходит их загрузка. Если namespace класса отличается
 | от его реального местоположения, то необходимо назначить соответствие такого
 | класса в файле '/app/Optional/MainConnector.php'.
 | Отключить, если используется другой способ.
 | Изначально: true
 |
 */
define( 'HLEB_PROJECT_CLASSES_AUTOLOAD', true );

/*
 |-----------------------------------------------------------------------------
 | URL completion
 |-----------------------------------------------------------------------------
 |
 | URLs may have or not have a slash at the end. This option allows setting
 | the presence of a (true) end as compulsory, otherwise the valid ending will
 | be without "/".
 | Default: true
 |
 |
 |-----------------------------------------------------------------------------
 | Завершение URL-адресов
 |-----------------------------------------------------------------------------
 |
 | Адреса URL могут иметь или не иметь слеш в конце. Данная опция позволяет
 | установить обязательным наличие (true) окончания, в противном случае
 | валидное окончание будет без "/".
 | Изначально: true
 |
 */
define('HLEB_PROJECT_ENDING_URL', false);

/*
 |-----------------------------------------------------------------------------
 | Transfer of the error log to the specified file
 |-----------------------------------------------------------------------------
 |
 | Logs with php errors and exceptions when creating routes are directed into
 | the folder '/storage/logs/'. Files will be numbered by calendar numbers.
 | When you disable a debug version, this is a convenient way to track errors.
 | Default: true
 |
 |
 |-----------------------------------------------------------------------------
 | Вывод лога ошибок в указанный файл
 |-----------------------------------------------------------------------------
 |
 | Направление логов с ошибками php и исключениями при создании роутов  в
 | папку /storage/logs/*. Файлы будут пронумерованы по календарным числам.
 | При отключении debug-версии это удобный способ отслеживания ошибок.
 | Изначально: true
 |
 */
define( 'HLEB_PROJECT_LOG_ON', true );

/*
 |-----------------------------------------------------------------------------
 | PHP error reporting level
 |-----------------------------------------------------------------------------
 |
 | There are many levels of errors in PHP, and it will be determined here,
 | which of them will be displayed.
 | Add all PHP errors to the report:  error_reporting(-1);
 | Disable error logging: error_reporting(0);
 | Default: E_ALL
 |
 |
 |-----------------------------------------------------------------------------
 | Уровень включения в отчёт ошибок PHP
 |-----------------------------------------------------------------------------
 |
 | В PHP много уровней ошибок, здесь определяется какие из них будут выведены.
 | Добавлять в отчет все ошибки PHP: error_reporting(-1);
 | Выключение протоколирования ошибок: error_reporting(0);
 | Изначально: E_ALL (Все ошибки)
 |
 */
error_reporting(E_ALL);

/*
 |-----------------------------------------------------------------------------
 | URL validity
 |-----------------------------------------------------------------------------
 |
 | Regular expression that restricts characters when generating a URL.
 | When a symbol is located outside the specified range, redirection
 | to the main page occurs.
 |
 | Latin and Cyrillic characters in any case:
 | "/^[a-z0-9а-яё\_\-\/\.]+$/ui"
 |
 | Latin and Cyrillic lowercase characters:
 | "/^[a-z0-9а-яё\_\-\/\.]+$/u"
 |
 | Latin lowercase characters without numbers, underscores and hyphens:
 | "/^[a-z\/\.]+$/"
 |
 | Default: "/^[a-z0-9а-яё\_\-\/\.]+$/u"
 |
 |
 |-----------------------------------------------------------------------------
 | Валидность URL
 |-----------------------------------------------------------------------------
 |
 | Регулярное выражение, ограничивающее символы при генерации URL.
 | При нахождении символа вне указанного диапазона происходит редирект на главную
 | страницу.
 |
 | Латинские и кириллические символы в любом регистре:
 | "/^[a-z0-9а-яё\_\-\/\.]+$/ui"
 |
 | Латинские и кириллические символы в нижнем регистре:
 | "/^[a-z0-9а-яё\_\-\/\.]+$/u"
 |
 | Латинские символы в нижнем регистре без цифр, нижнего подчеркивания и дефиса:
 | "/^[a-z\/\.]+$/"
 |
 | Изначально: "/^[a-z0-9а-яё\_\-\/\.]+$/u"
 |
 */
define( 'HLEB_PROJECT_VALIDITY_URL', "/^[А-Яа-яa-zA-Z0-9\_\-\/\.]+$/u" );

/*
 |-----------------------------------------------------------------------------
 | Website template
 |-----------------------------------------------------------------------------
 |
 | If you want to use your own template, then change the folder name.
 | Default template: default
 |
 |
 |-----------------------------------------------------------------------------
 | Шаблон сайта
 |-----------------------------------------------------------------------------
 |
 | Если вы хотите использовать свой шаблон, то измените название папки.
 | Шаблон по умолчанию: default
 |
 */
define( 'PR_VIEW_DIR', 'default' );

/*
 |-----------------------------------------------------------------------------
 | Localization file
 |-----------------------------------------------------------------------------
 |
 | The default localization is Russian (ru)
 |
 |-----------------------------------------------------------------------------
 | Загружаем файл локализации
 |-----------------------------------------------------------------------------
 |
 | Локализация по умолчанию русская (ru)
 |
 */
define( 'LANG', include_once(__DIR__ .'/app/Language/ru/lang.php') );

/*
 |-----------------------------------------------------------------------------
 | File paths
 |-----------------------------------------------------------------------------
 |
 | File paths for storing avatars, banners, etc.
 |
 |-----------------------------------------------------------------------------
 | Пути к файлам
 |-----------------------------------------------------------------------------
 |
 | Пути к файлам для хранения аватарок, баннеров и т.д.
 |
 */
 
define('AG_PATH_USERS_COVER', '/uploads/users/cover/');
define('AG_PATH_USERS_SMALL_COVER', '/uploads/users/cover/small/'); 
 
define('AG_PATH_SPACES_COVER', '/uploads/spaces/cover/');
define('AG_PATH_SPACES_SMALL_COVER', '/uploads/spaces/cover/small/');  
 
define('AG_PATH_USERS_AVATARS', '/uploads/users/avatars/');
define('AG_PATH_USERS_SMALL_AVATARS', '/uploads/users/avatars/small/');

define('AG_PATH_TOPICS_LOGOS', '/uploads/topics/logos/');
define('AG_PATH_TOPICS_SMALL_LOGOS', '/uploads/topics/logos/small/');

define('AG_PATH_SPACES_LOGOS', '/uploads/spaces/logos/');
define('AG_PATH_SPACES_SMALL_LOGOS', '/uploads/spaces/logos/small/');

define('AG_PATH_POSTS_CONTENT', '/uploads/posts/content/');
define('AG_PATH_POSTS_COVER', '/uploads/posts/cover/');
define('AG_PATH_POSTS_THUMB', '/uploads/posts/thumbnails/');

define('AG_PATH_FAVICONS', '/uploads/favicons/');
