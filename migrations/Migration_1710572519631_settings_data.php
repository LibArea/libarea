<?php

class Migration_1710572519631_settings_data extends \Phphleb\Migration\Src\StandardMigration
{
    public function up(PDO $db): void
    {
        $this->addSql("INSERT INTO `settings` (`val`, `value`) VALUES
               ('url', 'https://libarea.ru'),
               ('email', 'mail@libarea.ru'),
               ('name', 'LibArea'),
               ('title', 'LibArea — сообщество (скрипт мультиблога)'),
               ('img_path', '/assets/images/libarea.jpg'),
               ('img_path_web', '/assets/images/libarea-web.png'),
               ('banner_title', 'LibArea — сообщество'),
               ('banner_desc', 'Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт мультиблога'),

               ('feed_title', 'LibArea — сообщество (скрипт мультиблога)'),
               ('feed_desc', 'Темы по интересам, лента, блоги. Каталог сайтов. Платформа для коллективных блогов, скрипт мультиблога (сообщества) LibArea'),
               ('top_title', 'LibArea — популярные посты'),
               ('top_desc', 'Список популярных постов в ленте сообщества (по количеству ответов). Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт сообщества LibArea'),
               ('all_desc', 'Список всех постов в ленте сообщества. Скрипт сообщества LibArea'),

               ('ads_home_post', ''),
               ('ads_home_sidebar', ''),
               ('ads_home_menu', ''),
               ('ads_post_sidebar', ''),
               ('ads_post_footer', ''),
               ('ads_post_answer', ''),
               ('ads_catalog_home', ''),
               ('ads_catalog_cat_sidebar', ''),

               ('count_like_feed', 1),
               ('type_post_feed', 'classic');");
    }

    public function down(): void
    {
        $this->addSql("TRUNCATE TABLE `settings`;");
    }
}
