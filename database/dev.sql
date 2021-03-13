-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 13 2021 г., 16:05
-- Версия сервера: 8.0.23-0ubuntu0.20.04.1
-- Версия PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `myareadev`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `successfull` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Структура таблицы `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedvalidator` varchar(255) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `comment_type` enum('normal','admin','private') NOT NULL DEFAULT 'normal',
  `comment_post_id` int NOT NULL DEFAULT '0',
  `comment_user_id` int NOT NULL DEFAULT '0',
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_modified` timestamp NOT NULL DEFAULT '2020-12-31 09:00:00',
  `comment_ip` varbinary(42) DEFAULT NULL,
  `comment_order` smallint NOT NULL DEFAULT '0',
  `comment_on` smallint NOT NULL DEFAULT '0',
  `comment_after` smallint NOT NULL DEFAULT '0',
  `comment_votes` smallint NOT NULL DEFAULT '0',
  `comment_content` text NOT NULL,
  `comment_del` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `post_id` int UNSIGNED NOT NULL,
  `post_title` varchar(250) NOT NULL,
  `post_slug` varchar(128) NOT NULL,
  `post_cat_id` varchar(128) DEFAULT NULL,
  `post_blog_id` int DEFAULT NULL,
  `post_src` enum('web','api','mobile','phone') NOT NULL DEFAULT 'web',
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_user_id` int UNSIGNED NOT NULL,
  `post_visible` enum('all','friends') NOT NULL DEFAULT 'all',
  `post_ip_int` varchar(112) DEFAULT NULL,
  `post_votes` smallint NOT NULL DEFAULT '0',
  `post_karma` smallint NOT NULL DEFAULT '0',
  `post_comments` smallint NOT NULL DEFAULT '0',
  `post_content` text NOT NULL,
  `post_top` tinyint(1) NOT NULL DEFAULT '0',
  `post_is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Структура таблицы `taggings`
--

CREATE TABLE `taggings` (
  `taggings_id` int NOT NULL,
  `taggings_tag_id` int NOT NULL,
  `taggings_post_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Структура таблицы `tags`
--

CREATE TABLE `tags` (
  `tags_id` int NOT NULL,
  `tags_name` varchar(250) NOT NULL,
  `tags_slug` varchar(128) NOT NULL,
  `tags_description` varchar(250) NOT NULL,
  `tags_category_id` int NOT NULL DEFAULT '1',
  `tags_tip` int NOT NULL DEFAULT '1',
  `tags_permit_users` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `tags`
--

INSERT INTO `tags` (`tags_id`, `tags_name`, `tags_slug`, `tags_description`, `tags_category_id`, `tags_tip`, `tags_permit_users`) VALUES
(1, 'cms', 'cms', 'Системы управления сайтами...', 0, 1, 0),
(2, 'Вопросы', 'qa', 'Вопросы и ответы', 0, 2, 0),
(3, 'флуд', 'flud', 'Просто обычные разговоры', 0, 3, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(250) DEFAULT NULL,
  `reset_expire` datetime DEFAULT NULL,
  `activated` tinyint(1) NOT NULL,
  `activate_token` varchar(250) DEFAULT NULL,
  `activate_expire` varchar(250) DEFAULT NULL,
  `role` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` tinyint(1) DEFAULT '0',
  `avatar` varchar(250) DEFAULT NULL,
  `about` varchar(255) DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '0',
  `status` varchar(250) DEFAULT NULL,
  `my_blog` int DEFAULT NULL,
  `post_profile` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `name`, `email`, `password`, `reset_token`, `reset_expire`, `activated`, `activate_token`, `activate_expire`, `role`, `created_at`, `updated_at`, `deleted_at`, `avatar`, `about`, `rating`, `status`, `my_blog`, `post_profile`) VALUES
(1, 'AdreS', 'Олег', 'ss@sdf.ru', '$2y$10$oR5VZ.zk7IN/og70gQq/f.0Sb.GQJ33VZHIES4pyIpU3W2vF6aiaW', '', NULL, 1, NULL, NULL, 1, '2021-03-08 21:37:04', '2021-03-08 21:37:04', 0, '', 'Тестовый аккаунт', 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_comm`
--

CREATE TABLE `votes_comm` (
  `votes_comm_id` int NOT NULL,
  `votes_comm_item_id` int NOT NULL,
  `votes_comm_points` int NOT NULL,
  `votes_comm_ip` varchar(20) NOT NULL,
  `votes_comm_user_id` int NOT NULL DEFAULT '1',
  `votes_comm_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `votes_comm`
--

INSERT INTO `votes_comm` (`votes_comm_id`, `votes_comm_item_id`, `votes_comm_points`, `votes_comm_ip`, `votes_comm_user_id`, `votes_comm_date`) VALUES
(1, 8, 1, '46.251.85.131', 2, '2021-03-03 19:24:58'),
(2, 9, 1, '46.251.85.131', 1, '2021-03-03 19:25:55'),
(3, 11, 1, '46.251.85.131', 2, '2021-03-04 00:48:20'),
(4, 9, 1, '88.81.56.196', 5, '2021-03-05 12:01:41'),
(5, 16, 1, '83.102.243.49', 1, '2021-03-05 13:07:41'),
(6, 17, 1, '83.102.243.49', 2, '2021-03-05 13:07:52'),
(7, 15, 1, '83.102.243.49', 2, '2021-03-05 16:07:25'),
(8, 19, 1, '83.102.243.49', 2, '2021-03-05 16:07:50'),
(9, 21, 1, '149.62.2.161', 1, '2021-03-06 12:22:05'),
(10, 22, 1, '149.62.2.161', 2, '2021-03-06 12:24:11'),
(11, 11, 1, '88.81.49.131', 6, '2021-03-12 18:41:46'),
(12, 25, 1, '88.81.49.131', 6, '2021-03-12 23:04:56'),
(13, 24, 1, '88.81.49.131', 6, '2021-03-12 23:21:29'),
(14, 27, 1, '88.81.49.131', 1, '2021-03-13 00:42:52'),
(15, 26, 1, '46.251.67.240', 2, '2021-03-13 07:58:05'),
(16, 30, 1, '46.251.67.240', 1, '2021-03-13 11:52:31'),
(17, 30, 1, '83.149.44.30', 6, '2021-03-13 14:16:22');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `taggings`
--
ALTER TABLE `taggings`
  MODIFY `taggings_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `tags`
--
ALTER TABLE `tags`
  MODIFY `tags_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_comm`
--
ALTER TABLE `votes_comm`
  MODIFY `votes_comm_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
