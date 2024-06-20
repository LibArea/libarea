<?php
/**
 * Инициализация проекта после формирования конфигурации и автозагрузчика.
 */

use Hleb\Static\Settings;

$resources = Settings::getParam('main', 'allowed.resources');
$nonce = Settings::getParam('main', 'nonce');
$constraint = "default-src 'self' " . implode(' ', $resources['default-src']) .
    "; style-src 'self' " . implode(' ', $resources['style-src']) . 
    " 'nonce-{$nonce}'; script-src 'self'  " . implode(' ', $resources['script-src']) .
    " 'nonce-{$nonce}'; font-src " . implode(' ', $resources['font-src']) . "; img-src 'self' " . implode(' ', $resources['img-src']) . " data: blob:;";

header('Content-Security-Policy: ' . $constraint);
