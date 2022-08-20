<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddFieldItemModifiedDate extends AbstractMigration
{
    /**
     * ALTER TABLE `items` ADD `item_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `item_date`;
     */
    public function change(): void
    {
        $table = $this->table('items');
        $table->addColumn('item_modified', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'item_date'])
              ->update();
    }
}
