<?php

class Migration_1710544238328_settings_table extends \Phphleb\Migration\Src\StandardMigration
{
    public function up(PDO $db): void
    {
        $this->addSql("CREATE TABLE `settings` (
               `val` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
               `value` text COLLATE utf8mb4_general_ci NOT NULL,
               UNIQUE KEY `val` (`val`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    }

    public function down(): void
    {
        $this->addSql("DROP TABLE `settings`;");
    }
}
