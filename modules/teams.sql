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

--
-- TODO: Let's just try the multilingual option
-- 

CREATE TABLE IF NOT EXISTS `facets_translation` (
  `translation_id` int(11) NOT NULL AUTO_INCREMENT,
  `translation_facet_id` int(11) NOT NULL,
  `translation_code` varchar(20) NOT NULL,
  `translation_title`  	varchar(64) NOT NULL,
  `translation_seo_title`  	varchar(125) NOT NULL,
  `translation_description` varchar(255) NOT NULL,
  `translation_short_description` varchar(255) NOT NULL,
  `translation_info` text NOT NULL,
  PRIMARY KEY (`translation_id`),
  KEY `translation_facet_id` (`translation_facet_id`),
  KEY `translation_code` (`translation_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `facets_translation`
  ADD CONSTRAINT facet_id_code UNIQUE (translation_facet_id, translation_code);