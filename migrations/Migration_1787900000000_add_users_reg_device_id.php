<?php

class Migration_1787900000000_add_users_reg_device_id extends \Phphleb\Migration\Src\StandardMigration
{
    public function up(PDO $db): void
    {
        // Тип - отпечаток браузера (ClientJS getFingerprint, SHA-256 hex, до 64 символов)
        $this->addSql("ALTER TABLE `users` ADD COLUMN `reg_device_id` VARCHAR(64) NULL DEFAULT NULL AFTER `reg_ip`;");
        $this->addSql("ALTER TABLE `users` ADD INDEX `idx_reg_device_id` (`reg_device_id`);");
    }

    public function down(): void
    {
        $this->addSql("ALTER TABLE `users` DROP INDEX `idx_reg_device_id`;");
        $this->addSql("ALTER TABLE `users` DROP COLUMN `reg_device_id`;");
    }
}