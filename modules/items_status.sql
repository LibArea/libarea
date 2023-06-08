--
-- Структура таблицы `items_status`
-- Всё будет меняться, на данный момент нет смысла делать запрос. 
-- Данный функционал не поддерживается. В стадии разработки.
-- 
 
CREATE TABLE `items_status` (
  `status_id` int(11) NOT NULL auto_increment,
  `status_item_id` int(11) NOT NULL,
  `status_response` varchar(15) NOT NULL,
  `status_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY  (`status_id`),
  KEY `status_item_id` (`status_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 