<?php

/*
* The following language strings are used to send email messages.
*
* Следующие языковые строки используются для отправки email сообщений.
*/

return [
  'appealed_subject' => '{name} — 已联系你(@)',
  'appealed_message' => '<p>在网站上，有人找你谈话(@).查看：<p><p>{url}/p>',

  'changing_password_subject' => '{name} — 找回密码',
  'changing_password_message'  => '<p>你的密码重置链接：</p><p>{url}</p>',

  'test_subject' => '{name} — 邮件测试(管理面板)',
  'test_message' => '<p>此电子邮件是通过管理面板发送的测试邮件。</p>',
   
  'activate_email_subject' => '{name} — 激活邮箱',
  'activate_email_message' => '<p>要继续注册，请先激活你的邮箱。 按照这个链接：</p><p>{url}</p>',
   
  'invite_reg_subject' => '{name} — 邀请加入(邀请)',
  'invite_reg_message' => '<p>你已被邀请加入社区。 如果你同意。请点击链接：</p><p>{url}</p>',
   
  'footer' => '<p>此电子邮件是自动生成的。<br>你无需回复。</p><p>管理。<br>{name}</p>',

];
