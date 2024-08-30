<?php
/*
 * For notifications
 * Для уведомлений 
 */

return [

'list' => [
    ['id' => 1, 'icon' => 'mail', 'css' => 'green', 'lang' => 'notif_add_pm'],
    ['id' => 2, 'icon' => 'bi-lightbulb', 'css' => 'gray', 'lang' => 'notif_add_post'],
    ['id' => 3, 'icon' => 'reply', 'css' => 'brown', 'lang' => 'notif_add_answer'],
    ['id' => 4, 'icon' => 'comments', 'css' => 'gray-600', 'lang' => 'notif_add_comment'],

    ['id' => 5, 'icon' => 'comments', 'css' => 'gray', 'lang' => 'notif_add_comment_comment'],

    ['id' => 6, 'icon' => 'bi-lightbulb', 'css' => 'gray', 'lang' => 'notif_like_post'],
    ['id' => 7, 'icon' => 'bi-lightbulb', 'css' => 'gray', 'lang' => 'notif_like_answer'],
    ['id' => 8, 'icon' => 'bi-lightbulb', 'css' => 'gray', 'lang' => 'notif_like_comment'],

    ['id' => 10, 'icon' => 'user', 'css' => 'gray', 'lang' => 'notif_references_post'],
    ['id' => 11, 'icon' => 'user', 'css' => 'gray', 'lang' => 'notif_references_amswer'],
    ['id' => 12, 'icon' => 'user', 'css' => 'gray', 'lang' => 'notif_references_comment'],

    ['id' => 20, 'icon' => 'flag', 'css' => 'red', 'lang' => 'notif_add_flag'],
    ['id' => 21, 'icon' => 'flag', 'css' => 'red', 'lang' => 'notif_add_audit'],

    ['id' => 30, 'icon' => 'link', 'css' => 'sky', 'lang' => 'notif_add_website'],
    ['id' => 31, 'icon' => 'link', 'css' => 'sky', 'lang' => 'notif_edit_website'],
    ['id' => 32, 'icon' => 'link', 'css' => 'sky', 'lang' => 'notif_approved_website'],
    ['id' => 33, 'icon' => 'link', 'css' => 'sky', 'lang' => 'notif_add_website_subscription'],
    ['id' => 34, 'icon' => 'link', 'css' => 'sky', 'lang' => 'notif_add_website_comment'],
    ['id' => 35, 'icon' => 'link', 'css' => 'sky', 'lang' => 'notif_add_website_comment_reply'],
    ['id' => 35, 'icon' => 'link', 'css' => 'sky', 'lang' => 'notif_add_website_comment_references'],
	
	],
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