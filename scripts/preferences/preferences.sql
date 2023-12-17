--
-- Структура таблицы `users_preferences`
-- Всё будет меняться, на данный момент нет смысла делать запрос. 
-- Данный функционал не поддерживается. В стадии разработки.
-- 
 
CREATE TABLE `users_preferences` (
  `user_id` int(11) NOT NULL,
  `facet_id` int(11) default NULL,
  `type` int(6) default NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;