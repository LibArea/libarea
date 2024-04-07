<?php

class Migration_1710540686993_from_dump extends \Phphleb\Migration\Src\StandardMigration
{
    public function up(PDO $db): void
    {
        // Если первая версия проекта не установлена, то устанавливаем из дампа.
        // Эта проверка нужна, так как при переходе на миграции эти таблицы могут существовать.
        $stmt = $db->query("SHOW TABLES LIKE 'users'");
        if ($stmt->rowCount() === 0) {
            $initContent = file_get_contents(__DIR__ . '/resources/init_dev_dump.sql');
            foreach ($this->splitText($initContent) as $query) {
                if (trim($query)) {
                    $this->addSql($query);
                }
            }
        } else {
            // Чтобы миграция внеслась как выполненная необходимо выполнить запрос.
            $this->addSql('SELECT 1 FROM `users`;');
        }
    }

    /**
     * Откат миграции. Внимание! Все таблицы будут удалены.
     */
    public function down(): void
    {
        $initContent = file_get_contents(__DIR__ . '/resources/init_down_dev.sql');

        foreach ($this->splitText($initContent) as $query) {
            if (trim($query)) {
                $this->addSql($query);
            }
        }

    }

    /**
     * Разбивает составной список SQL-запросов на отдельные.
     */
    private function splitText(string $sql): array
    {
        $lines = explode("\n", $sql);
        $filteredLines = array_filter($lines, static fn($line) => !str_starts_with($line, "-"));

        $result = [];
        $currentPart = [];
        foreach ($filteredLines as $line) {
            if (str_starts_with($line, "ALTER TABLE") ||
                str_starts_with($line, "INSERT INTO") ||
                str_starts_with($line, "CREATE TABLE") ||
                str_starts_with($line, "DROP TABLE")
            ) {
                if (!empty($currentPart)) {
                    $result[] = implode("\n", $currentPart);
                    $currentPart = [];
                }
            }
            $currentPart[] = $line;
        }

        if (!empty($currentPart)) {
            $result[] = implode("\n", $currentPart);
        }

        return $result;

    }
}
