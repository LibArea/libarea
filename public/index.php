<?php
/**
 * @author  Foma Tuturov <fomiash@yandex.ru>
 */

// All calls are sent to this file.
// Все вызовы направляются в этот файл.
define('HLEB_START', microtime(true));
define('HLEB_FRAME_VERSION', "1.6.60");
define('HLEB_PUBLIC_DIR', __DIR__);

// General headers.
// Общие заголовки.
// Content Security Policy
$_SERVER['nonce'] = bin2hex(random_bytes((int)'12'));
header("Content-Security-Policy: default-src 'self' https://www.google.com https://www.youtube.com https://mc.yandex.ru; style-src 'self' 'unsafe-inline'; script-src 'self' https://www.google.com https://www.gstatic.com https://mc.yandex.ru  'nonce-".$_SERVER['nonce']."'; img-src 'self' data: blob:;");
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