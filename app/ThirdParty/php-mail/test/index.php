<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once('../vendor/autoload.php');

/* attention
Überprüfe deine Einstellungen in der php.ini. Hier die Beispieleinstellung innerhalb von eine xampp apache Umgebung

[mail function]
; For Win32 only.
; http://php.net/smtp
SMTP=localhost
; http://php.net/smtp-port
smtp_port=25

; For Win32 only.
; http://php.net/sendmail-from
sendmail_from = me@example.com

; For Unix only.  You may supply arguments as well (default: "sendmail -t -i").
; http://php.net/sendmail-path
sendmail_path = C:\xampp\mailtodisk\mailtodisk.exe
 */

$mail = new JacksonJeans\Mail();
$send = $mail->setFrom('julian.tietz@textil24.net','Julian')
    ->setTo('general.julian.tietz@outlook.de','Julian')
    ->setSubject('Test Mail')
    ->setText('Hi, Julian')
    ->send();

var_dump($send);
