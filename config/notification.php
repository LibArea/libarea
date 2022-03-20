<?php
/*
 * For notifications
 * Для уведомлений 
 */

return [
    ['id' => 1, 'icon' => 'bi-envelope green', 'lang' => 'notif.add.pm'],
    ['id' => 2, 'icon' => 'bi bi-lightbulb', 'lang' => 'notif.add.post'],
    ['id' => 3, 'icon' => 'bi-reply gray-400', 'lang' => 'notif.add.answer'],
    ['id' => 4, 'icon' => 'bi-chat-dots', 'lang' => 'notif.add.comment'],

    ['id' => 5, 'icon' => 'bi bi-lightbulb', 'lang' => 'notif.add.chat'],

    ['id' => 6, 'icon' => 'bi bi-lightbulb', 'lang' => 'notif.like.post'],
    ['id' => 7, 'icon' => 'bi bi-lightbulb', 'lang' => 'notif.like.answer'],
    ['id' => 8, 'icon' => 'bi bi-lightbulb', 'lang' => 'notif.like.comment'],

    ['id' => 10, 'icon' => 'bi bi-person', 'lang' => 'notif.references.post'],
    ['id' => 11, 'icon' => 'bi bi-person', 'lang' => 'notif.references.amswer'],
    ['id' => 12, 'icon' => 'bi bi-person', 'lang' => 'notif.references.comment'],

    ['id' => 20, 'icon' => 'bi-exclamation-diamond red', 'lang' => 'notif.add.flag'],
    ['id' => 21, 'icon' => 'bi-exclamation-diamond red', 'lang' => 'notif.add.audit'],

    ['id' => 30, 'icon' => 'bi-link-45deg sky-500', 'lang' => 'notif.add.website'],
    ['id' => 31, 'icon' => 'bi-link-45deg sky-500', 'lang' => 'notif.edit.website'],
    ['id' => 32, 'icon' => 'bi-link-45deg sky-500', 'lang' => 'notif.approved.website'],
    ['id' => 33, 'icon' => 'bi-link-45deg sky-500', 'lang' => 'notif.add.website.subscription'],
    ['id' => 34, 'icon' => 'bi-link-45deg sky-500', 'lang' => 'notif.add.website.comment'],
    ['id' => 35, 'icon' => 'bi-link-45deg sky-500', 'lang' => 'notif.add.website.comment.reply'],
    ['id' => 35, 'icon' => 'bi-link-45deg sky-500', 'lang' => 'notif.add.website.comment.references'],
];

/*
CREATE TABLE `notification_type` (
  `type_id` int(11) NOT NULL,
  `action_type_id` int(11) NOT NULL COMMENT 'Тип уведомлений',
  `with_icon` varchar(64) NOT NULL COMMENT 'Иконка уведомления',
  `key_lang` varchar(64) NOT NULL COMMENT 'Ключ перевода'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `notification_type` (`type_id`, `action_type_id`, `with_icon`, `key_lang`) VALUES

(1, 1, 'bi-envelope green', 'notif.add.pm'),
(2, 2, 'bi bi-lightbulb', 'notif.add.post'),
(3, 3, 'bi-reply gray', 'notif.add.answer'),
(4, 4, 'bi-chat-dots', 'notif.add.comment'),
(5, 5, 'bi bi-lightbulb', 'notif.add.chat'),
(6, 6, 'bi bi-lightbulb', 'notif.like.post'),
(7, 7, 'bi bi-lightbulb', 'notif.like.answer'),
(8, 8, 'bi bi-lightbulb', 'notif.like.comment'),
(9, 10, 'bi bi-person', 'notif.references.post'),
(10, 11, 'bi bi-person', 'notif.references.amswer'),
(11, 12, 'bi bi-person', 'notif.references.comment'),
(12, 20, 'bi-exclamation-diamond red', 'notif.add.flag'),
(13, 21, 'bi-exclamation-diamond red', 'notif.add.audit'),
(14, 30, 'bi-link-45deg sky', 'notif.add.website'),
(15, 31, 'bi-link-45deg sky', 'notif.edit.website'),
(16, 32, 'bi-link-45deg sky', 'notif.approved.website'),
(17, 33, 'bi-link-45deg sky', 'notif.add.website.subscription'),
(18, 34, 'bi-link-45deg sky', 'notif.add.website.comment'),
(19, 35, 'bi-link-45deg sky', 'notif.add.website.comment.reply'),
(20, 35, 'bi-link-45deg sky', 'notif.add.website.comment.references');

ALTER TABLE `notification_type`
  ADD PRIMARY KEY (`type_id`); 

ALTER TABLE `notification_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
*/