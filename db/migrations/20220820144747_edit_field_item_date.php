<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EditFieldItemDate extends AbstractMigration
{
    /**
     * ALTER TABLE `items` CHANGE `item_date` `item_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
     */
    public function change(): void
    {
        $items = $this->table('items');
        $items->changeColumn('item_date', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->save();
    }
}
