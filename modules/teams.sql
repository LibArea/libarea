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

CREATE TABLE `facets_translation` (
  `translation_facet_id` int(11) NOT NULL,
  `translation_code` varchar(12) NOT NULL,
  `translation_title` varchar(64) NOT NULL,
  `translation_seo_title` varchar(64) NOT NULL,
  `translation_description` varchar(255) NOT NULL,
  `translation_short_description` varchar(255) NOT NULL,
  `translation_info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `facets_translation` (`translation_facet_id`, `translation_code`, `translation_title`, `translation_seo_title`, `translation_description`, `translation_short_description`, `translation_info`) VALUES
(1, 'ru', 'SEO', 'Поисковая оптимизация (SEO)', 'Поисковая оптимизация — это комплекс мер по внутренней и внешней оптимизации для поднятия позиций сайта в результатах выдачи поисковых систем.', 'Поисковая оптимизация', 'Комплекс мер по внутренней и внешней оптимизации для поднятия позиций сайта в результатах выдачи поисковых систем по определённым запросам пользователей.\r\n\r\n**Поисковая оптимизация** — это способ использования правил поиска поисковых систем для улучшения текущего естественного ранжирования веб-сайтов в соответствующих поисковых системах. \r\n\r\nЦелью SEO является предоставление экологического решения для саморекламы для веб-сайта, позволяющего веб-сайту занимать лидирующие позиции в отрасли, чтобы получить преимущества бренда. \r\n\r\nSEO включает как внешнее, так и внутреннее SEO. \r\n\r\nSEO средства получить от поисковых систем больше бесплатного трафика, разумное планирование с точки зрения структуры веб-сайта, плана построения контента, взаимодействия с пользователем и общения, страниц и т.д., чтобы сделать веб-сайт более подходящим для принципов индексации поисковых систем. \r\n\r\nПовышение пригодности веб-сайтов для поисковых систем также называется Оптимизацией для поисковых систем, может не только улучшить эффект SEO, но и сделать информацию, относящуюся к веб-сайту, отображаемую в поисковой системе, более привлекательной для пользователей.'),
(1, 'en', 'SEO', 'Поисковая оптимизация (SEO)', 'Search engine optimization is a set of measures for internal and external optimization to raise the position of the site in the results of search engines.', 'Search Engine Optimization', ''),
(1, 'ro', 'SEO', 'Поисковая оптимизация (SEO)', 'Optimizarea pentru motoarele de căutare este un set de măsuri de optimizare internă și externă pentru a ridica poziția site-ului în rezultatele motoarelor de căutare.', 'Optimizare motor de căutare', ''),
(1, 'fr', 'SEO', 'Optimisation des moteurs de recherche (SEO)', 'L\'optimisation des moteurs de recherche est un ensemble de mesures d\'optimisation interne et externe pour augmenter la position du site dans les résultats des moteurs de recherche.', 'optimisation du moteur de recherche', ''),
(1, 'de', 'SEO', 'Suchmaschinenoptimierung (SEO)', 'Suchmaschinenoptimierung ist eine Reihe von Maßnahmen zur internen und externen Optimierung, um die Position der Website in den Ergebnissen von Suchmaschinen zu erhöhen.', 'Suchmaschinenoptimierung', ''),
(1, 'zh_TW', 'SEO', '搜索引擎优化 (SEO)', '搜索引擎优化是为提高网站在搜索引擎结果中的位置而进行的内部和外部优化的一套措施', '搜索引擎优化', ''),
(1, 'zh_CN', 'SEO', '搜索引擎优化 (SEO)', '搜索引擎优化是为提高网站在搜索引擎结果中的位置而进行的内部和外部优化的一套措施', '搜索引擎优化', ''),


(2, 'ru', 'Интересные сайты', '', '', '', ''),
(2, 'en', 'Interesting sites', '', '', '', ''),
(2, 'ro', 'Site-uri interesante', '', '', '', ''),
(2, 'fr', 'Sites intéressants', '', '', '', ''),
(2, 'de', 'Interessante Seiten', '', '', '', ''),
(2, 'zh_TW', '有趣的网站', '', '', '', ''),
(2, 'zh_CN', '有趣的网站', '', '', '', '');


(3, 'ru', 'Социальные сети', '', '', '', ''),
(3, 'en', 'Social networks', '', '', '', ''),
(3, 'ro', 'Retele sociale', '', '', '', ''),
(3, 'fr', 'Réseaux sociaux', '', '', '', ''),
(3, 'de', 'Soziale Netzwerke', '', '', '', ''),
(3, 'zh_TW', '有社交网络', '', '', '', ''),
(3, 'zh_CN', '社交网络', '', '', '', '');


ALTER TABLE `facets_translation`
  ADD UNIQUE KEY `facet_id_code` (`translation_facet_id`,`translation_code`),
  ADD KEY `translation_facet_id` (`translation_facet_id`),
  ADD KEY `translation_code` (`translation_code`);