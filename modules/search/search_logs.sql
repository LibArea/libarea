--
-- Структура таблицы `search_logs`
-- 
 
CREATE TABLE `search_logs` (
  `id` int(11) NOT NULL,
  `request` text NOT NULL,
  `action_type` varchar(32) NOT NULL COMMENT 'Catalog, site...',
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `add_ip` varchar(45) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `count_results` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `search_logs`
  ADD PRIMARY KEY (`id`); 

ALTER TABLE `search_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT; 