--
-- Структура таблицы `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) NOT NULL,
  `answer_post_id` int(11) NOT NULL DEFAULT 0,
  `answer_user_id` int(11) NOT NULL DEFAULT 0,
  `answer_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `answer_modified` timestamp NOT NULL DEFAULT '2020-12-30 09:00:00',
  `answer_published` tinyint(1) NOT NULL DEFAULT 1,
  `answer_ip` varbinary(16) DEFAULT NULL,
  `answer_order` smallint(6) NOT NULL DEFAULT 0,
  `answer_after` smallint(6) NOT NULL DEFAULT 0,
  `answer_votes` smallint(6) NOT NULL DEFAULT 0,
  `answer_content` text NOT NULL,
  `answer_lo` int(11) NOT NULL DEFAULT 0,
  `answer_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `answers`
--

INSERT INTO `answers` (`answer_id`, `answer_post_id`, `answer_user_id`, `answer_date`, `answer_modified`, `answer_published`, `answer_ip`, `answer_order`, `answer_after`, `answer_votes`, `answer_content`, `answer_lo`, `answer_is_deleted`) VALUES
(1, 3, 1, '2021-04-29 04:41:27', '2020-12-30 09:00:00', 1, 0x3132372e302e302e31, 0, 0, 0, 'Первый ответ в теме', 0, 0),
(2, 1, 2, '2021-07-01 07:34:52', '2021-08-15 22:50:53', 1, 0x3132372e302e302e31, 0, 0, 0, 'Интересно, спасибо. Вы забыли указать, что можно задавать вопросы в чате (ссылка в footer) этого сайта.', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `audits`
--

CREATE TABLE `audits` (
  `audit_id` int(11) NOT NULL,
  `audit_type` varchar(16) DEFAULT NULL COMMENT 'Посты, ответы, комментарии',
  `audit_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `audit_user_id` int(11) NOT NULL DEFAULT 0,
  `audit_content_id` int(11) NOT NULL DEFAULT 0,
  `audit_read_flag` tinyint(1) DEFAULT 0 COMMENT 'Состояние прочтения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `badges`
--

CREATE TABLE `badges` (
  `badge_id` int(11) NOT NULL,
  `badge_icon` varchar(550) NOT NULL,
  `badge_tl` int(6) DEFAULT NULL,
  `badge_score` int(6) DEFAULT NULL,
  `badge_title` varchar(150) NOT NULL,
  `badge_description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `badges`
--

INSERT INTO `badges` (`badge_id`, `badge_icon`, `badge_tl`, `badge_score`, `badge_title`, `badge_description`) VALUES
(1, '<i title=\"Тестер\" class=\"bi bi-bug\"></i>', 0, 0, 'Тестер', 'Сообщение об ошибке, которое понравилось команде сайта.');

-- --------------------------------------------------------

--
-- Структура таблицы `badges_user`
--

CREATE TABLE `badges_user` (
  `bu_id` int(11) NOT NULL,
  `bu_badge_id` int(6) NOT NULL,
  `bu_user_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_post_id` int(11) NOT NULL DEFAULT 0,
  `comment_answer_id` int(11) NOT NULL DEFAULT 0,
  `comment_comment_id` int(11) NOT NULL DEFAULT 0,
  `comment_user_id` int(11) NOT NULL DEFAULT 0,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_modified` timestamp NOT NULL DEFAULT '2020-12-30 09:00:00',
  `comment_published` tinyint(1) NOT NULL DEFAULT 1,
  `comment_ip` varbinary(16) DEFAULT NULL,
  `comment_after` smallint(6) NOT NULL DEFAULT 0,
  `comment_votes` smallint(6) NOT NULL DEFAULT 0,
  `comment_content` text NOT NULL,
  `comment_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `facets`
--

CREATE TABLE `facets` (
  `facet_id` int(11) NOT NULL,
  `facet_title` varchar(64) DEFAULT NULL,
  `facet_description` varchar(255) DEFAULT NULL,
  `facet_short_description` varchar(160) DEFAULT NULL,
  `facet_info` text DEFAULT NULL,
  `facet_slug` varchar(32) DEFAULT NULL,
  `facet_img` varchar(255) DEFAULT 'facet-default.png',
  `facet_cover_art` varchar(255) DEFAULT 'cover_art.jpeg',
  `facet_add_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `facet_seo_title` varchar(255) DEFAULT NULL,
  `facet_merged_id` int(11) DEFAULT 0 COMMENT 'с кем слита',
  `facet_top_level` tinyint(1) NOT NULL DEFAULT 0,
  `facet_user_id` int(11) NOT NULL DEFAULT 1,
  `facet_tl` tinyint(1) DEFAULT 0,
  `facet_related` varchar(255) DEFAULT NULL,
  `facet_post_related` varchar(255) DEFAULT NULL,
  `facet_the_day` tinyint(1) NOT NULL DEFAULT 0,
  `facet_focus_count` int(11) DEFAULT 0,
  `facet_count` int(11) DEFAULT 0,
  `facet_sort` int(11) NOT NULL DEFAULT 0,
  `facet_type` varchar(32) NOT NULL DEFAULT 'topic' COMMENT 'Topic or Blog...',
  `facet_is_web` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Show or not in the site catalog',
  `facet_is_soft` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Show or not in the software catalog',
  `facet_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets`
--

INSERT INTO `facets` (`facet_id`, `facet_title`, `facet_description`, `facet_short_description`, `facet_info`, `facet_slug`, `facet_img`, `facet_cover_art`, `facet_add_date`, `facet_seo_title`, `facet_merged_id`, `facet_top_level`, `facet_user_id`, `facet_tl`, `facet_related`, `facet_post_related`, `facet_the_day`, `facet_focus_count`, `facet_count`, `facet_sort`, `facet_type`, `facet_is_web`, `facet_is_soft`, `facet_is_deleted`) VALUES
(1, 'SEO', 'Поисковая оптимизация — это комплекс мер по внутренней и внешней оптимизации для поднятия позиций сайта в результатах выдачи поисковых систем.', 'Краткое описание темы...', 'Комплекс мер по внутренней и внешней оптимизации для поднятия позиций сайта в результатах выдачи поисковых систем по определённым запросам пользователей.\r\n\r\n**Поисковая оптимизация** — это способ использования правил поиска поисковых систем для улучшения текущего естественного ранжирования веб-сайтов в соответствующих поисковых системах. \r\n\r\nЦелью SEO является предоставление экологического решения для саморекламы для веб-сайта, позволяющего веб-сайту занимать лидирующие позиции в отрасли, чтобы получить преимущества бренда. \r\n\r\nSEO включает как внешнее, так и внутреннее SEO. \r\n\r\nSEO средства получить от поисковых систем больше бесплатного трафика, разумное планирование с точки зрения структуры веб-сайта, плана построения контента, взаимодействия с пользователем и общения, страниц и т.д., чтобы сделать веб-сайт более подходящим для принципов индексации поисковых систем. \r\n\r\nПовышение пригодности веб-сайтов для поисковых систем также называется Оптимизацией для поисковых систем, может не только улучшить эффект SEO, но и сделать информацию, относящуюся к веб-сайту, отображаемую в поисковой системе, более привлекательной для пользователей.', 'seo', 't-1-1625149922.jpeg', 'cover_art.jpeg', '2021-06-28 09:29:20', 'Поисковая оптимизация (SEO)', 0, 0, 1, 0, '0', '0', 0, 1, 1, 0, 'topic', 0, 0, 0),
(2, 'Интересные сайты', 'Интересные сайты в Интернете. Обзоры, интересные материалы, переводы. Статьи.', 'Краткое описание темы...', 'Интересные сайты в Интернете. Обзоры, интересные материалы, переводы. Статьи.\r\n\r\nПросто вводная страница... В разработке...', 'sites', 't-2-1625149821.jpeg', 'cover_art.jpeg', '2021-06-28 09:29:20', 'Интересные сайты', 0, 0, 1, 0, '1', '3', 0, 1, 1, 0, 'topic', 0, 0, 0),
(3, 'Веб-разработка', 'Веб-разработка — это работа, связанная с разработкой веб-сайта для Интернета (World Wide Web) или интрасети (частной сети).', 'Веб-разработка', 'Веб-разработка — это работа, связанная с разработкой веб-сайта для Интернета (World Wide Web) или интрасети (частной сети). Веб-разработка может варьироваться от разработки простой статической страницы с открытым текстом до сложных веб-приложений, электронного бизнеса и социальных сетей.', 'web-development', 'topic-default.png', 'cover_art.jpeg', '2021-11-05 11:04:41', 'Веб-разработка', 0, 0, 1, 0, '0', '0', 0, 0, 0, 0, 'topic', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_links_relation`
--

CREATE TABLE `facets_links_relation` (
  `relation_facet_id` int(11) DEFAULT 0,
  `relation_link_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_links_relation`
--

INSERT INTO `facets_links_relation` (`relation_facet_id`, `relation_link_id`) VALUES
(3, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_merge`
--

CREATE TABLE `facets_merge` (
  `merge_id` int(11) NOT NULL,
  `merge_add_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `merge_source_id` int(11) NOT NULL DEFAULT 0,
  `merge_target_id` int(11) NOT NULL DEFAULT 0,
  `merge_user_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `facets_posts_relation`
--

CREATE TABLE `facets_posts_relation` (
  `relation_facet_id` int(11) DEFAULT 0,
  `relation_post_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_posts_relation`
--

INSERT INTO `facets_posts_relation` (`relation_facet_id`, `relation_post_id`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_relation`
--

CREATE TABLE `facets_relation` (
  `facet_parent_id` int(11) DEFAULT NULL,
  `facet_chaid_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_relation`
--

INSERT INTO `facets_relation` (`facet_parent_id`, `facet_chaid_id`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `facets_signed`
--

CREATE TABLE `facets_signed` (
  `signed_id` int(11) NOT NULL,
  `signed_facet_id` int(11) NOT NULL,
  `signed_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `facets_signed`
--

INSERT INTO `facets_signed` (`signed_id`, `signed_facet_id`, `signed_user_id`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` mediumint(8) NOT NULL,
  `favorite_user_id` mediumint(8) NOT NULL,
  `favorite_tid` int(11) NOT NULL,
  `favorite_type` tinyint(1) NOT NULL COMMENT '1 посты, 2 комментарии'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `file_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(32) DEFAULT NULL,
  `file_content_id` int(11) UNSIGNED DEFAULT NULL,
  `file_user_id` int(11) UNSIGNED DEFAULT NULL,
  `file_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `invitations`
--

CREATE TABLE `invitations` (
  `invitation_id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) DEFAULT 0,
  `invitation_code` varchar(32) DEFAULT NULL,
  `invitation_email` varchar(100) DEFAULT NULL,
  `add_time` datetime NOT NULL,
  `add_ip` varchar(45) DEFAULT NULL,
  `active_expire` tinyint(1) DEFAULT 0,
  `active_time` datetime DEFAULT NULL,
  `active_ip` varchar(45) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 0,
  `active_uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `links`
--

CREATE TABLE `links` (
  `link_id` int(11) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `link_url_domain` varchar(255) DEFAULT NULL,
  `link_title` varchar(255) NOT NULL,
  `link_content` text NOT NULL,
  `link_published` tinyint(1) NOT NULL DEFAULT 1,
  `link_user_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Кто добавил',
  `link_date` datetime NOT NULL DEFAULT current_timestamp(),
  `link_type` int(6) NOT NULL DEFAULT 0 COMMENT 'Тип сайта (0 - общий, 1 - блог, 2 - энциклопедия)',
  `link_status` int(6) NOT NULL DEFAULT 200 COMMENT 'Статус сайта (200, 403, 404)',
  `link_status_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Когда была проверка статуса',
  `link_votes` int(6) DEFAULT 0,
  `link_count` int(6) DEFAULT 1,
  `link_is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `links`
--

INSERT INTO `links` (`link_id`, `link_url`, `link_url_domain`, `link_title`, `link_content`, `link_published`, `link_user_id`, `link_date`, `link_type`, `link_status`, `link_status_date`, `link_votes`, `link_count`, `link_is_deleted`) VALUES
(1, 'https://phphleb.ru', 'phphleb.ru', '«HLEB» — микрофреймворк (php)', 'Документация и описание Micro-Framework(а) использующий базовую реализацию MVC на PHP. Установить, настройка, структура проекта. Маршрутизация, контроллеры и модели.', 1, 1, '2021-11-05 19:09:37', 0, 200, '2021-11-05 19:09:37', 0, 1, 0),
(2, 'https://agouti.ru', 'agouti.ru', '«Agouti» — дискуссии (скрипт)', 'Комментарии, дискуссии на различные темы. Новости сети отсортированные по новым, горячим, важным. Скрипт под лицензией MIT.', 1, 1, '2021-11-05 19:15:19', 0, 200, '2021-11-05 19:15:19', 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `message_sender_id` int(11) DEFAULT NULL COMMENT 'Отправитель',
  `message_dialog_id` int(11) DEFAULT NULL,
  `message_content` text DEFAULT NULL,
  `message_add_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `message_sender_remove` tinyint(1) DEFAULT 0,
  `message_recipient_remove` tinyint(1) DEFAULT 0,
  `message_receipt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `messages_dialog`
--

CREATE TABLE `messages_dialog` (
  `dialog_id` int(11) NOT NULL,
  `dialog_sender_id` int(11) DEFAULT NULL COMMENT 'Отправитель',
  `dialog_sender_unread` int(11) DEFAULT NULL COMMENT 'Отправитель, 0 непрочитано',
  `dialog_recipient_id` int(11) DEFAULT NULL COMMENT 'Получатель',
  `dialog_recipient_unread` int(11) DEFAULT NULL COMMENT 'Получатель, 0 непрочитано',
  `dialog_add_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `dialog_update_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `dialog_sender_count` int(11) DEFAULT NULL COMMENT 'Отправитель кол.',
  `dialog_recipient_count` int(11) DEFAULT NULL COMMENT 'Получатель кол.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `moderations`
--

CREATE TABLE `moderations` (
  `mod_id` int(11) NOT NULL,
  `mod_moderates_user_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Кто меняет',
  `mod_moderates_user_tl` int(11) NOT NULL DEFAULT 0 COMMENT 'Модераторы от tl4',
  `mod_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `mod_updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `mod_post_id` int(11) NOT NULL DEFAULT 0 COMMENT 'id поста',
  `mod_content_id` int(11) NOT NULL DEFAULT 0 COMMENT 'id контента',
  `mod_action` varchar(255) NOT NULL COMMENT 'deleted, restored и т.д.',
  `mod_reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `notification_sender_id` int(11) DEFAULT NULL COMMENT 'Отправитель',
  `notification_recipient_id` int(11) DEFAULT 0 COMMENT 'Получает ID',
  `notification_action_type` int(4) DEFAULT NULL COMMENT 'Тип оповещения',
  `notification_connection_type` int(11) DEFAULT NULL COMMENT 'Данные источника',
  `notification_url` varchar(255) DEFAULT NULL COMMENT 'URL источника',
  `notification_add_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `notification_read_flag` tinyint(1) DEFAULT 0 COMMENT 'Состояние прочтения',
  `notification_is_deleted` tinyint(1) UNSIGNED DEFAULT 0 COMMENT 'Удаление'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_slug` varchar(128) NOT NULL,
  `post_type` smallint(1) NOT NULL DEFAULT 0,
  `post_translation` smallint(1) NOT NULL DEFAULT 0,
  `post_draft` smallint(1) NOT NULL DEFAULT 0,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_modified` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_published` tinyint(1) NOT NULL DEFAULT 1,
  `post_user_id` int(10) UNSIGNED NOT NULL,
  `post_ip` varbinary(16) DEFAULT NULL,
  `post_after` smallint(6) NOT NULL DEFAULT 0 COMMENT 'id первого ответа',
  `post_votes` smallint(6) NOT NULL DEFAULT 0,
  `post_karma` smallint(6) NOT NULL DEFAULT 0,
  `post_answers_count` int(11) DEFAULT 0,
  `post_comments_count` int(11) DEFAULT 0,
  `post_hits_count` int(11) DEFAULT 0,
  `post_content` text NOT NULL,
  `post_content_img` varchar(255) DEFAULT NULL,
  `post_thumb_img` varchar(255) DEFAULT NULL,
  `post_related` varchar(255) DEFAULT NULL,
  `post_merged_id` int(11) NOT NULL DEFAULT 0 COMMENT 'id с чем объединен',
  `post_is_recommend` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Рекомендованные посты',
  `post_closed` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - пост закрыт',
  `post_tl` smallint(1) NOT NULL DEFAULT 0 COMMENT 'Видимость по уровню доверия',
  `post_lo` int(11) NOT NULL DEFAULT 0 COMMENT 'Id лучшего ответа',
  `post_top` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - пост поднят',
  `post_url` varchar(255) DEFAULT NULL,
  `post_url_domain` varchar(255) DEFAULT NULL,
  `post_focus_count` int(11) DEFAULT 0,
  `post_is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`post_id`, `post_title`, `post_slug`, `post_type`, `post_translation`, `post_draft`, `post_date`, `post_modified`, `post_published`, `post_user_id`, `post_ip`, `post_after`, `post_votes`, `post_karma`, `post_answers_count`, `post_comments_count`, `post_hits_count`, `post_content`, `post_content_img`, `post_thumb_img`, `post_related`, `post_merged_id`, `post_is_recommend`, `post_closed`, `post_tl`, `post_lo`, `post_top`, `post_url`, `post_url_domain`, `post_focus_count`, `post_is_deleted`) VALUES
(1, 'Ответы на некоторые вопросы (FAQ)', 'answer-qa', 0, 0, 0, '2021-02-27 12:08:09', '2021-10-27 00:26:06', 1, 1, 0x3132372e302e302e31, 0, 0, 0, 1, 0, 6, 'Превью поста на главной странице сайта формируется из первого абзаца текста. Мы забираем первый абзац и делаем превью. Сайт испольлзует MVC модель, если кто знаком с ней, то не особо трудно будет разобраться.\r\n\r\n### Где находятся конфиг сайта?\r\n\r\nЕсть 3 основополагающих файла конфигурации (There are 3 configuration files):\r\n\r\n* *dbase.config.php* — подключение к базе данных (connecting to the database)\r\n\r\n* *config.ini* — основные настройки (basic settings)\r\n\r\n* *start.hleb.php* — константы (constants)\r\n\r\n### Где находятся шаблоны сайта?\r\n\r\n```\r\n/resources/views/default\r\n```\r\n\r\n### Как мне изменить страницы с документацией?\r\n\r\nСлужебные страницы на этом сайте находятся: [/info](/info)\r\n\r\nА сам текст в Markdown разметке:\r\n\r\n```txt\r\n/resources/views/default/info/md/index.md\r\n```\r\n\r\n### Как мне поменять язык сайта?\r\n\r\nПо умолчанию на сайте используется русский язык.\r\n\r\nПереводы находится в папке:  `/app/Language/`\r\n\r\nВы можете переключать языки, в файле: `general.php` \r\n\r\nНайти:\r\n```php\r\n\'lang\' => \'ru\',\r\n```\r\n\r\n---\r\n\r\nBy default, the site uses Russian.\r\n\r\nThe translations are located in the folder: `general.php` find:\r\n\r\n```php\r\n\'lang\' => \'ru\',\r\n```\r\n\r\nThe transfers themselves are stored: `/app/Language/`', '', NULL, '2', 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0),
(2, 'Где можно почитать документацию?', 'docs-post', 0, 0, 0, '2021-02-27 12:15:58', '2021-11-05 11:26:31', 1, 2, 0x3132372e302e302e31, 0, 1, 0, 0, 0, 7, 'Страница документации Agouti находится в стадии разработки... \r\n\r\n[https://agouti.info/](https://agouti.info/)\r\n\r\nКак она будет завершена, об этом будет сообщено дополнительно. Сам сайт создан на PHP Микрофреймворк HLEB. Все основные настройки,  можно найти на этом сайте:\r\n\r\n[https://phphleb.ru/ru/v1/](https://phphleb.ru/ru/v1/)\r\n\r\n', '', NULL, '1', 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0),
(3, 'Medium — платформа для создания контента', 'medium-where-good-ideas-find-you', 0, 0, 0, '2021-04-29 04:35:13', '2021-04-29 04:35:13', 1, 1, 0x3132372e302e302e31, 0, 0, 0, 1, 0, 1, 'Medium — это платформа для создания контента, основанная соучредителем Blogger и Twitter Эван Уильямсом. Многие компании используют Medium в качестве платформы для публикации...', '2021/c-1624954734.webp', NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `posts_signed`
--

CREATE TABLE `posts_signed` (
  `signed_id` int(11) NOT NULL,
  `signed_post_id` int(11) NOT NULL,
  `signed_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_user_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Индификатор участника id',
  `report_type` varchar(50) NOT NULL COMMENT 'Тип контента',
  `report_content_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Id контента',
  `report_reason` varchar(255) NOT NULL COMMENT 'Причина флага',
  `report_url` varchar(255) NOT NULL,
  `report_date` datetime NOT NULL DEFAULT current_timestamp(),
  `report_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `stop_words`
--

CREATE TABLE `stop_words` (
  `stop_id` int(11) NOT NULL,
  `stop_word` varchar(50) DEFAULT NULL,
  `stop_add_uid` int(11) NOT NULL DEFAULT 0 COMMENT 'Кто добавил',
  `stop_space_id` int(11) NOT NULL DEFAULT 0 COMMENT '0 - глобально',
  `stop_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_login` varchar(50) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_activated` tinyint(1) DEFAULT 0,
  `user_limiting_mode` tinyint(1) DEFAULT 0,
  `user_reg_ip` varchar(45) DEFAULT NULL,
  `user_trust_level` int(11) NOT NULL COMMENT 'Уровень доверия. По умолчанию 0 (5 - админ)',
  `user_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `user_updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `user_invitation_available` int(11) NOT NULL DEFAULT 0,
  `user_invitation_id` int(11) NOT NULL DEFAULT 0,
  `user_template` varchar(12) NOT NULL DEFAULT 'default',
  `user_lang` varchar(2) NOT NULL DEFAULT 'ru',
  `user_whisper` varchar(255) NOT NULL,
  `user_avatar` varchar(255) NOT NULL DEFAULT 'noavatar.png',
  `user_cover_art` varchar(255) NOT NULL DEFAULT 'cover_art.jpeg',
  `user_color` varchar(12) NOT NULL DEFAULT '#f56400',
  `user_about` varchar(250) DEFAULT NULL,
  `user_website` varchar(50) DEFAULT NULL,
  `user_location` varchar(50) DEFAULT NULL,
  `user_public_email` varchar(50) DEFAULT NULL,
  `user_skype` varchar(50) DEFAULT NULL,
  `user_twitter` varchar(50) DEFAULT NULL,
  `user_telegram` varchar(50) DEFAULT NULL,
  `user_vk` varchar(50) DEFAULT NULL,
  `user_rating` int(11) DEFAULT 0,
  `user_my_post` int(11) DEFAULT 0 COMMENT 'Пост выведенный в профиль',
  `user_ban_list` tinyint(1) DEFAULT 0,
  `user_hits_count` int(11) DEFAULT 0,
  `user_up_count` int(11) DEFAULT 0,
  `user_is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_name`, `user_email`, `user_password`, `user_activated`, `user_limiting_mode`, `user_reg_ip`, `user_trust_level`, `user_created_at`, `user_updated_at`, `user_invitation_available`, `user_invitation_id`, `user_template`, `user_lang`, `user_whisper`, `user_avatar`, `user_cover_art`, `user_color`, `user_about`, `user_website`, `user_location`, `user_public_email`, `user_skype`, `user_twitter`, `user_telegram`, `user_vk`, `user_rating`, `user_my_post`, `user_ban_list`, `user_hits_count`, `user_up_count`, `user_is_deleted`) VALUES
(1, 'AdreS', 'Олег', 'ss@sdf.ru', '$2y$10$oR5VZ.zk7IN/og70gQq/f.0Sb.GQJ33VZHIES4pyIpU3W2vF6aiaW', 1, 0, '127.0.0.1', 5, '2021-03-08 21:37:04', '2021-03-08 21:37:04', 0, 0, 'default', 'ru', '', 'img_1.jpg', 'cover_art.jpeg', '#f56400', 'Тестовый аккаунт', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
(2, 'test', NULL, 'test@test.ru', '$2y$10$Iahcsh3ima0kGqgk6S/SSui5/ETU5bQueYROFhOsjUU/z1.xynR7W', 1, 0, '127.0.0.1', 1, '2021-04-30 07:42:52', '2021-04-30 07:42:52', 0, 0, 'default', 'ru', '', 'noavatar.png', 'cover_art.jpeg', '#339900', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users_activate`
--

CREATE TABLE `users_activate` (
  `activate_id` int(11) NOT NULL,
  `activate_date` datetime NOT NULL,
  `activate_user_id` int(11) NOT NULL,
  `activate_code` varchar(50) NOT NULL,
  `activate_flag` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_agent_logs`
--

CREATE TABLE `users_agent_logs` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_user_id` int(10) UNSIGNED NOT NULL,
  `log_user_browser` varchar(64) NOT NULL,
  `log_user_os` varchar(64) NOT NULL,
  `log_user_ip` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `users_agent_logs`
--

INSERT INTO `users_agent_logs` (`log_id`, `log_date`, `log_user_id`, `log_user_browser`, `log_user_os`, `log_user_ip`) VALUES
(1, '2021-09-21 01:09:38', 1, 'Firefox 92.0', 'Windows', '127.0.0.1'),
(2, '2021-09-21 01:57:57', 2, 'Chrome 93.0.4577.82', 'Windows', '127.0.0.1'),
(3, '2021-10-18 19:43:05', 1, 'Firefox 93.0', 'Windows', '127.0.0.1'),
(4, '2021-10-27 00:24:03', 1, 'Firefox 93.0', 'Windows', '127.0.0.1'),
(5, '2021-11-05 11:01:34', 1, 'Firefox 94.0', 'Windows', '127.0.0.1');

-- --------------------------------------------------------

--
-- Структура таблицы `users_auth_tokens`
--

CREATE TABLE `users_auth_tokens` (
  `auth_id` int(11) NOT NULL,
  `auth_user_id` int(11) NOT NULL,
  `auth_selector` varchar(255) NOT NULL,
  `auth_hashedvalidator` varchar(255) NOT NULL,
  `auth_expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_banlist`
--

CREATE TABLE `users_banlist` (
  `banlist_id` int(11) NOT NULL,
  `banlist_user_id` int(11) NOT NULL,
  `banlist_ip` varchar(45) NOT NULL,
  `banlist_bandate` timestamp NOT NULL DEFAULT current_timestamp(),
  `banlist_int_num` int(11) NOT NULL,
  `banlist_int_period` varchar(20) NOT NULL,
  `banlist_status` tinyint(1) NOT NULL DEFAULT 1,
  `banlist_autodelete` tinyint(1) NOT NULL DEFAULT 0,
  `banlist_cause` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_email_activate`
--

CREATE TABLE `users_email_activate` (
  `id` int(11) NOT NULL,
  `pubdate` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_code` varchar(50) NOT NULL,
  `email_activate_flag` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_setting`
--

CREATE TABLE `users_setting` (
  `setting_id` int(11) NOT NULL,
  `setting_user_id` int(11) UNSIGNED NOT NULL,
  `setting_email_pm` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Написал ПМ',
  `setting_email_appealed` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Обратился @',
  `setting_email_post` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Написал пост',
  `setting_email_answer` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Ответил',
  `setting_email_comment` tinyint(1) UNSIGNED DEFAULT NULL COMMENT 'Прокомментировал'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_trust_level`
--

CREATE TABLE `users_trust_level` (
  `trust_id` int(11) NOT NULL,
  `trust_name` varchar(85) NOT NULL,
  `trust_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users_trust_level`
--

INSERT INTO `users_trust_level` (`trust_id`, `trust_name`, `trust_count`) VALUES
(0, 'Посетитель', 0),
(1, 'Пользователь', 0),
(2, 'Участник', 0),
(3, 'Постоялец', 0),
(4, 'Лидер', 0),
(5, 'Персонал', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `votes_answer`
--

CREATE TABLE `votes_answer` (
  `votes_answer_id` int(11) NOT NULL,
  `votes_answer_item_id` int(11) NOT NULL,
  `votes_answer_points` int(11) NOT NULL,
  `votes_answer_ip` varchar(45) NOT NULL,
  `votes_answer_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_answer_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_comment`
--

CREATE TABLE `votes_comment` (
  `votes_comment_id` int(11) NOT NULL,
  `votes_comment_item_id` int(11) NOT NULL,
  `votes_comment_points` int(11) NOT NULL,
  `votes_comment_ip` varchar(45) NOT NULL,
  `votes_comment_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_comment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_link`
--

CREATE TABLE `votes_link` (
  `votes_link_id` int(11) NOT NULL,
  `votes_link_item_id` int(11) NOT NULL,
  `votes_link_points` int(11) NOT NULL,
  `votes_link_ip` varchar(45) NOT NULL,
  `votes_link_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_link_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `votes_post`
--

CREATE TABLE `votes_post` (
  `votes_post_id` int(11) NOT NULL,
  `votes_post_item_id` int(11) NOT NULL,
  `votes_post_points` int(11) NOT NULL,
  `votes_post_ip` varchar(45) NOT NULL,
  `votes_post_user_id` int(11) NOT NULL DEFAULT 1,
  `votes_post_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `votes_post`
--

INSERT INTO `votes_post` (`votes_post_id`, `votes_post_item_id`, `votes_post_points`, `votes_post_ip`, `votes_post_user_id`, `votes_post_date`) VALUES
(1, 2, 1, '127.0.0.1', 1, '2021-08-16 16:29:32');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `answer_link_id_2` (`answer_post_id`,`answer_date`),
  ADD KEY `answer_date` (`answer_date`),
  ADD KEY `answer_user_id` (`answer_user_id`,`answer_date`),
  ADD KEY `answer_post_id` (`answer_post_id`,`answer_order`);

--
-- Индексы таблицы `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `audit_type` (`audit_type`),
  ADD KEY `audit_user_id` (`audit_user_id`),
  ADD KEY `audit_content_id` (`audit_content_id`);

--
-- Индексы таблицы `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badge_id`);

--
-- Индексы таблицы `badges_user`
--
ALTER TABLE `badges_user`
  ADD PRIMARY KEY (`bu_id`),
  ADD KEY `bu_badge_id` (`bu_badge_id`),
  ADD KEY `bu_user_id` (`bu_user_id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_link_id_2` (`comment_post_id`,`comment_date`),
  ADD KEY `comment_date` (`comment_date`),
  ADD KEY `comment_user_id` (`comment_user_id`,`comment_date`);

--
-- Индексы таблицы `facets`
--
ALTER TABLE `facets`
  ADD PRIMARY KEY (`facet_id`),
  ADD KEY `facet_slug` (`facet_slug`),
  ADD KEY `facet_merged_id` (`facet_merged_id`),
  ADD KEY `facet_type` (`facet_type`);

--
-- Индексы таблицы `facets_links_relation`
--
ALTER TABLE `facets_links_relation`
  ADD KEY `relation_facet_id` (`relation_facet_id`),
  ADD KEY `relation_content_id` (`relation_link_id`);

--
-- Индексы таблицы `facets_merge`
--
ALTER TABLE `facets_merge`
  ADD PRIMARY KEY (`merge_id`),
  ADD KEY `merge_source_id` (`merge_source_id`),
  ADD KEY `merge_target_id` (`merge_target_id`),
  ADD KEY `merge_user_id` (`merge_user_id`);

--
-- Индексы таблицы `facets_posts_relation`
--
ALTER TABLE `facets_posts_relation`
  ADD KEY `relation_facet_id` (`relation_facet_id`),
  ADD KEY `relation_content_id` (`relation_post_id`);

--
-- Индексы таблицы `facets_relation`
--
ALTER TABLE `facets_relation`
  ADD UNIQUE KEY `facet_parent_id` (`facet_parent_id`,`facet_chaid_id`);

--
-- Индексы таблицы `facets_signed`
--
ALTER TABLE `facets_signed`
  ADD PRIMARY KEY (`signed_id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `favorite_user_id` (`favorite_id`),
  ADD KEY `favorite_id` (`favorite_tid`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`),
  ADD UNIQUE KEY `file_path` (`file_path`),
  ADD KEY `file_user_id` (`file_user_id`);

--
-- Индексы таблицы `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`invitation_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `invitation_code` (`invitation_code`),
  ADD KEY `active_time` (`active_time`),
  ADD KEY `active_ip` (`active_ip`),
  ADD KEY `active_status` (`active_status`);

--
-- Индексы таблицы `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`link_id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `message_dialog_id` (`message_dialog_id`),
  ADD KEY `message_sender_id` (`message_sender_id`),
  ADD KEY `message_add_time` (`message_add_time`),
  ADD KEY `message_sender_remove` (`message_sender_remove`),
  ADD KEY `message_recipient_remove` (`message_recipient_remove`),
  ADD KEY `message_sender_receipt` (`message_receipt`);

--
-- Индексы таблицы `messages_dialog`
--
ALTER TABLE `messages_dialog`
  ADD PRIMARY KEY (`dialog_id`),
  ADD KEY `dialog_recipient_id` (`dialog_recipient_id`),
  ADD KEY `dialog_sender_id` (`dialog_sender_id`),
  ADD KEY `dialog_update_time` (`dialog_update_time`),
  ADD KEY `dialog_add_time` (`dialog_add_time`);

--
-- Индексы таблицы `moderations`
--
ALTER TABLE `moderations`
  ADD PRIMARY KEY (`mod_id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `notification_recipient_read_flag` (`notification_recipient_id`,`notification_read_flag`),
  ADD KEY `notification_sender_id` (`notification_sender_id`),
  ADD KEY `notification_action_type` (`notification_action_type`),
  ADD KEY `notification_add_time` (`notification_add_time`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `post_date` (`post_date`),
  ADD KEY `post_user_id` (`post_user_id`,`post_date`);

--
-- Индексы таблицы `posts_signed`
--
ALTER TABLE `posts_signed`
  ADD PRIMARY KEY (`signed_id`);

--
-- Индексы таблицы `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `status` (`report_status`);

--
-- Индексы таблицы `stop_words`
--
ALTER TABLE `stop_words`
  ADD PRIMARY KEY (`stop_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `users_activate`
--
ALTER TABLE `users_activate`
  ADD PRIMARY KEY (`activate_id`);

--
-- Индексы таблицы `users_agent_logs`
--
ALTER TABLE `users_agent_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_user_ip` (`log_user_ip`);

--
-- Индексы таблицы `users_auth_tokens`
--
ALTER TABLE `users_auth_tokens`
  ADD PRIMARY KEY (`auth_id`);

--
-- Индексы таблицы `users_banlist`
--
ALTER TABLE `users_banlist`
  ADD PRIMARY KEY (`banlist_id`),
  ADD KEY `banlist_ip` (`banlist_ip`),
  ADD KEY `banlist_user_id` (`banlist_user_id`);

--
-- Индексы таблицы `users_email_activate`
--
ALTER TABLE `users_email_activate`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_setting`
--
ALTER TABLE `users_setting`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `setting_user_id` (`setting_user_id`);

--
-- Индексы таблицы `users_trust_level`
--
ALTER TABLE `users_trust_level`
  ADD PRIMARY KEY (`trust_id`);

--
-- Индексы таблицы `votes_answer`
--
ALTER TABLE `votes_answer`
  ADD PRIMARY KEY (`votes_answer_id`),
  ADD KEY `votes_answer_item_id` (`votes_answer_item_id`,`votes_answer_user_id`),
  ADD KEY `votes_answer_ip` (`votes_answer_item_id`,`votes_answer_ip`),
  ADD KEY `votes_answer_user_id` (`votes_answer_user_id`);

--
-- Индексы таблицы `votes_comment`
--
ALTER TABLE `votes_comment`
  ADD PRIMARY KEY (`votes_comment_id`),
  ADD KEY `votes_comment_item_id` (`votes_comment_item_id`,`votes_comment_user_id`),
  ADD KEY `votes_comment_ip` (`votes_comment_item_id`,`votes_comment_ip`),
  ADD KEY `votes_comment_user_id` (`votes_comment_user_id`);

--
-- Индексы таблицы `votes_link`
--
ALTER TABLE `votes_link`
  ADD PRIMARY KEY (`votes_link_id`),
  ADD KEY `votes_link_item_id` (`votes_link_item_id`,`votes_link_user_id`),
  ADD KEY `votes_link_ip` (`votes_link_item_id`,`votes_link_ip`),
  ADD KEY `votes_link_user_id` (`votes_link_user_id`);

--
-- Индексы таблицы `votes_post`
--
ALTER TABLE `votes_post`
  ADD PRIMARY KEY (`votes_post_id`),
  ADD KEY `votes_post_item_id` (`votes_post_item_id`,`votes_post_user_id`),
  ADD KEY `votes_post_ip` (`votes_post_item_id`,`votes_post_ip`),
  ADD KEY `votes_post_user_id` (`votes_post_user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `audits`
--
ALTER TABLE `audits`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `badges`
--
ALTER TABLE `badges`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `badges_user`
--
ALTER TABLE `badges_user`
  MODIFY `bu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `facets`
--
ALTER TABLE `facets`
  MODIFY `facet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `facets_merge`
--
ALTER TABLE `facets_merge`
  MODIFY `merge_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `facets_signed`
--
ALTER TABLE `facets_signed`
  MODIFY `signed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `invitations`
--
ALTER TABLE `invitations`
  MODIFY `invitation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `links`
--
ALTER TABLE `links`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `messages_dialog`
--
ALTER TABLE `messages_dialog`
  MODIFY `dialog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `moderations`
--
ALTER TABLE `moderations`
  MODIFY `mod_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `posts_signed`
--
ALTER TABLE `posts_signed`
  MODIFY `signed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `stop_words`
--
ALTER TABLE `stop_words`
  MODIFY `stop_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users_activate`
--
ALTER TABLE `users_activate`
  MODIFY `activate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_agent_logs`
--
ALTER TABLE `users_agent_logs`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users_auth_tokens`
--
ALTER TABLE `users_auth_tokens`
  MODIFY `auth_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_banlist`
--
ALTER TABLE `users_banlist`
  MODIFY `banlist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_email_activate`
--
ALTER TABLE `users_email_activate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_setting`
--
ALTER TABLE `users_setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_answer`
--
ALTER TABLE `votes_answer`
  MODIFY `votes_answer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_comment`
--
ALTER TABLE `votes_comment`
  MODIFY `votes_comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_link`
--
ALTER TABLE `votes_link`
  MODIFY `votes_link_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `votes_post`
--
ALTER TABLE `votes_post`
  MODIFY `votes_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
