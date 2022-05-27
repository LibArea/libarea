--
-- Структура таблицы `teams`
-- Всё будет менять. На данный момент нет смысла делать запрос. 
-- Данный функционал не поддерживается. В стадии разработки.
-- 
 
CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(50) DEFAULT NULL,
  `team_content` text,
  `team_user_id` int(11) NOT NULL,
  `team_type` varchar(32) NOT NULL COMMENT 'тип команды, к чему относится?',
  `team_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `team_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `team_is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 
 
CREATE TABLE `teams_content_relation` (
  `team_id` int(11) NOT NULL,
  `team_content_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `teams_users_relation` (
  `team_id` int(11) NOT NULL,
  `team_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 

--
-- Индексы
-- 

ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`);

ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `teams_content_relation`
    ADD UNIQUE INDEX `tc_relation` (`team_id`, `team_content_id`);  
  
ALTER TABLE `teams_users_relation`
    ADD UNIQUE INDEX `tu_relation` (`team_id`, `team_user_id`);
    
ALTER TABLE `teams_content_relation` ADD INDEX(`team_content_id`); 
ALTER TABLE `teams_users_relation` ADD INDEX(`team_id`);   