-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 13 2021 г., 06:44
-- Версия сервера: 10.4.17-MariaDB
-- Версия PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



--
-- База данных: `111`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `successfull` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedvalidator` varchar(255) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_type` enum('normal','admin','private') NOT NULL DEFAULT 'normal',
  `comment_post_id` int(11) NOT NULL DEFAULT 0,
  `comment_user_id` int(11) NOT NULL DEFAULT 0,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_modified` timestamp NOT NULL DEFAULT '2020-12-31 12:00:00',
  `comment_ip` varbinary(42) DEFAULT NULL,
  `comment_order` smallint(6) NOT NULL DEFAULT 0,
  `comment_on` smallint(6) NOT NULL DEFAULT 0,
  `comment_after` smallint(6) NOT NULL DEFAULT 0,
  `comment_votes` smallint(6) NOT NULL DEFAULT 0,
  `comment_content` text NOT NULL,
  `comment_del` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `post_title` varchar(250) NOT NULL,
  `post_slug` varchar(128) NOT NULL,
  `post_cat_id` varchar(128) DEFAULT NULL,
  `post_blog_id` int(11) DEFAULT NULL,
  `post_src` enum('web','api','mobile','phone') NOT NULL DEFAULT 'web',
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `edit_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_user_id` int(10) UNSIGNED NOT NULL,
  `post_visible` enum('all','friends') NOT NULL DEFAULT 'all',
  `post_ip_int` varchar(112) DEFAULT NULL,
  `post_votes` smallint(6) NOT NULL DEFAULT 0,
  `post_karma` smallint(6) NOT NULL DEFAULT 0,
  `post_comments` smallint(6) NOT NULL DEFAULT 0,
  `post_content` text NOT NULL,
  `post_top` tinyint(1) NOT NULL DEFAULT 0,
  `post_is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`post_id`, `post_title`, `post_slug`, `post_cat_id`, `post_blog_id`, `post_src`, `post_date`, `edit_date`, `post_user_id`, `post_visible`, `post_ip_int`, `post_votes`, `post_karma`, `post_comments`, `post_content`, `post_top`, `post_is_delete`) VALUES
(1, 'Первый пост', 'pervyj-post', NULL, NULL, 'web', '2021-03-13 05:44:10', '2021-03-13 05:44:10', 1, 'all', '127.0.0.1', 0, 0, 0, 'Тест первого поста\r\n\r\n> цитата', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `taggings`
--

CREATE TABLE `taggings` (
  `taggings_id` int(11) NOT NULL,
  `taggings_tag_id` int(11) NOT NULL,
  `taggings_post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `taggings`
--

INSERT INTO `taggings` (`taggings_id`, `taggings_tag_id`, `taggings_post_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE `tags` (
  `tags_id` int(11) NOT NULL,
  `tags_name` varchar(250) NOT NULL,
  `tags_slug` varchar(128) NOT NULL,
  `tags_description` varchar(250) NOT NULL,
  `tags_category_id` int(11) NOT NULL DEFAULT 1,
  `tags_tip` int(11) NOT NULL DEFAULT 1,
  `tags_permit_users` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tags`
--

INSERT INTO `tags` (`tags_id`, `tags_name`, `tags_slug`, `tags_description`, `tags_category_id`, `tags_tip`, `tags_permit_users`) VALUES
(1, 'cms', 'cms', 'Системы управления сайтами...', 0, 1, 0),
(2, 'Вопросы', 'qa', 'Вопросы и ответы', 0, 2, 0),
(3, 'флуд', 'flud', 'Просто обычные разговоры', 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(250) NOT NULL,
  `reset_expire` datetime DEFAULT NULL,
  `activated` tinyint(1) NOT NULL,
  `activate_token` varchar(250) DEFAULT NULL,
  `activate_expire` varchar(250) DEFAULT NULL,
  `role` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` tinyint(1) DEFAULT 0,
  `avatar` varchar(250) NOT NULL,
  `about` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `status` varchar(250) NOT NULL,
  `my_blog` int(11) NOT NULL,
  `post_profile` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `name`, `email`, `password`, `reset_token`, `reset_expire`, `activated`, `activate_token`, `activate_expire`, `role`, `created_at`, `updated_at`, `deleted_at`, `avatar`, `about`, `rating`, `status`, `my_blog`, `post_profile`) VALUES
(1, 'AdreS', 'Олег', 'ss@sdf.ru', '$2y$10$oR5VZ.zk7IN/og70gQq/f.0Sb.GQJ33VZHIES4pyIpU3W2vF6aiaW', '', NULL, 1, NULL, NULL, 1, '2021-03-08 21:37:04', '2021-03-08 21:37:04', 0, '', '\"&gt;&lt;script&gt;alert(\"cookie: \"+document.cookie)&lt;/script&gt;,', 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_comm`
--

CREATE TABLE `votes_comm` (
  `votes_comm_id` int(11) NOT NULL,
  `votes_comm_item_id` int(11) NOT NULL,
  `votes_comm_points` int(11) NOT NULL,
  `votes_comm_ip` varchar(20) NOT NULL,
  `votes_comm_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_comm_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_link_id_2` (`comment_post_id`,`comment_date`),
  ADD KEY `comment_date` (`comment_date`),
  ADD KEY `comment_user_id` (`comment_user_id`,`comment_date`),
  ADD KEY `comment_post_id` (`comment_post_id`,`comment_order`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `post_date` (`post_date`),
  ADD KEY `post_user_id` (`post_user_id`,`post_date`);

--
-- Индексы таблицы `taggings`
--
ALTER TABLE `taggings`
  ADD PRIMARY KEY (`taggings_id`);

--
-- Индексы таблицы `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tags_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `votes_comm`
--
ALTER TABLE `votes_comm`
  ADD PRIMARY KEY (`votes_comm_id`),
  ADD KEY `votes_comm_item_id` (`votes_comm_item_id`,`votes_comm_user_id`),
  ADD KEY `votes_comm_ip` (`votes_comm_item_id`,`votes_comm_ip`),
  ADD KEY `votes_comm_user_id` (`votes_comm_user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `taggings`
--
ALTER TABLE `taggings`
  MODIFY `taggings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `tags`
--
ALTER TABLE `tags`
  MODIFY `tags_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_comm`
--
ALTER TABLE `votes_comm`
  MODIFY `votes_comm_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

