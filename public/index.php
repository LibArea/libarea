<?php
/**
 * @author  Foma Tuturov <fomiash@yandex.ru>
 */

// All calls are sent to this file.
// Все вызовы направляются в этот файл.

define('HLEB_START', microtime(true));
define('HLEB_FRAME_VERSION', "1.5.58");
define('HLEB_PUBLIC_DIR', __DIR__);
define('TEMPLATE_DIR', realpath(__DIR__ . '/../resources/views/'));

// Загружаем файл конфигурации и локализации
$GLOBALS['conf'] = include( __DIR__ .'/../config.inc.php');
define('LANG', include_once(__DIR__ .'/../app/Language/'. $GLOBALS['conf']['lang']. '/lang.php'));

// General headers.
// Общие заголовки.
// Content Security Policy (ВКЛЮЧИТЬ в производстве)
header("Content-Security-Policy: default-src 'self' https://www.google.com  https://www.gstatic.com; img-src 'self'; object-src 'none'");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload;");
header("Referrer-Policy: no-referrer-when-downgrade");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
// ...

// Initialization.
// Инициализация.
require __DIR__ . '/../vendor/phphleb/framework/bootstrap.php';

exit();


