--
-- Структура таблицы `teams`
-- Всё будет менять. На данный момент нет смысла делать запрос. 
-- Данный функционал не поддерживается. В стадии разработки.
-- 
 
CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `content` text,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(32) NOT NULL COMMENT 'тип команды, к чему относится?',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 
 
CREATE TABLE `teams_relation` (
  `team_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `teams_users` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_access` int(6) NOT NULL DEFAULT '1' COMMENT 'Права: 1 - общие, 2 - расширенные, 3 - полные',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 

--
-- Индексы
-- 

ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `teams_relation`
  ADD KEY `team_id` (`team_id`) USING BTREE,
  ADD KEY `item_id` (`item_id`) USING BTREE;
  
ALTER TABLE `teams_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teams_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  