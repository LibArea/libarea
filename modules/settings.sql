--
-- Структура таблицы `settings`
-- Всё будет менять. На данный момент нет смысла делать запрос. 
-- Данный функционал не поддерживается. В стадии разработки.
-- 
 
CREATE TABLE `settings` (
  `val` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `value` text COLLATE utf8mb4_general_ci NOT NULL,
  UNIQUE KEY `val` (`val`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `settings` (`val`, `value`) VALUES
('url', 'https://libarea.ru'),
('email', 'mail@libarea.ru'),
('name', 'LibArea'),
('title', 'LibArea — сообщество (скрипт мультиблога)'),
('img_path', '/assets/images/libarea.jpg'),
('img_path_web', '/assets/images/libarea-web.png'),
('banner_title', 'LibArea — сообщество'),
('banner_desc', 'Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт мультиблога'),

('feed_title', 'LibArea — сообщество (скрипт мультиблога)'),
('feed_desc', 'Темы по интересам, лента, блоги. Каталог сайтов. Платформа для коллективных блогов, скрипт мультиблога (сообщества) LibArea'),
('top_title', 'LibArea — популярные посты'),
('top_desc', 'Список популярных постов в ленте сообщества (по количеству ответов). Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт сообщества LibArea'),
('all_title', 'LibArea — все посты'),
('all_desc', 'Список всех постов в ленте сообщества. Скрипт сообщества LibArea'),

('count_like_feed', 1),
('type_post_feed', 'classic');