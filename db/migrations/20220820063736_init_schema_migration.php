<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InitSchemaMigration extends AbstractMigration
{
    public function change(): void
    {
         $sql = file_get_contents('db/data/dev.sql', true);
         $this->query($sql);
    }
}
