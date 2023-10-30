<?php

/*
* The following language strings are used to send email messages.
*
* Следующие языковые строки используются для отправки email сообщений.
*/

return [
  'appealed_subject' => '{name} contacted you (@)',
  'appealed_message' => '<p>On the site you were contacted (@). Look:<p><p>{url}</p>',

  'changing_password_subject' => '{name} — password recovery',
  'changing_password_message'  => '<p>Your password reset link:</p><p>{url}</p>',

  'test_subject' => '{name} — mail testing (admin panel)',
  'test_message' => '<p>This email is a test email sent through the admin panel.</p>',

  'activate_email_subject' => '{name} — email activation',
  'activate_email_message' => '<p>To continue registration, activate your E-mail. Follow this link:</p><p>{url}</p>',

  'new_email_subject' => '{name} — change email',
  'new_email_message' => '<p>To continue, activate your Email by clicking on the link:</p><p>{url}</p>',

  'invite_reg_subject' => '{name} — invitation to join (invite)',
  'invite_reg_message' => '<p>You have been invited to join the community. If you agree. then follow the link:</p><p>{url}</p>',

  'footer' => '<p>This email was generated automatically.<br>You don\'t need to reply to it.</p><p>Administration.<br>{name}</p>',
];
