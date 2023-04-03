<?php

/*
* The following language strings are used to send email messages.
*
* Следующие языковые строки используются для отправки email сообщений.
*/

return [
  'appealed_subject' => '{name} — к вам обратились (@)',
  'appealed_message' => '<p>На сайте, к вам обратились (@). Посмотреть:<p><p>{url}</p>',

  'changing_password_subject' => '{name} — восстановление пароля',
  'changing_password_message'  => '<p>Ваша ссылка для изменения пароля:</p><p>{url}</p>',

  'test_subject' => '{name} — тестирование почты (админ-панель)',
  'test_message' => '<p>Это письмо является тестовым, отправленным через админ-панель.</p>',

  'activate_email_subject' => '{name} — активация email',
  'activate_email_message' => '<p>Для продолжения регистрации активируйте свой E-mail. Перейдите по ссылке:</p><p>{url}</p>',

  'new_email_subject' => '{name} — смена email',
  'new_email_message' => '<p>Для продолжения, активируйте свой E-mail перейдя по ссылке:</p><p>{url}</p>',

  'invite_reg_subject' => '{name} — приглашение присоединиться (инвайт)',
  'invite_reg_message' => '<p>Вас пригласили присоединиться к сообществу. Если вы согласны. то перейдите по ссылке:</p><p>{url}</p>',

  'footer' => '<p>Это письмо создано автоматически.<br>Отвечать на него не надо.</p><p>Администрация.<br>{name}</p>',

];
